<?php declare(strict_types=1);

namespace AppBundle\Controller\Api;

use AppBundle\Cache\ApiService;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Repository\ServerRepository;
use AppBundle\Entity\Server;
use Statusboard\ControllerHelpers\ResponseHelper;
use Statusboard\ControllerHelpers\ApiError\ServerErrors;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ServerController
 * @Route("/api/server")
 *
 * @package AppBundle\Controller\Api
 */
class ServerController extends ApiController {

    /**
     * @Route("", name="api_server_getall")
     * @Method("GET")
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getAllServers(Request $request) {
        $onlyActive = $request->query->getBoolean('onlyActive', false);

        /** @var ServerRepository $serverRepository */
        $serverRepository = $this->getDoctrine()->getRepository(Server::class);

        if ($onlyActive) {
            $servers = $serverRepository->findBy(['isDisabled' => false]);
        } else {
            $servers = $serverRepository->findAll();
        }

        $response = [];
        /** @var Server $server */
        foreach ($servers as $server) {
            $response[] = $server->toArray();
        }
        return $this->json($response, JsonResponse::HTTP_OK);
    }

    /**
     * @Route("", name="api_server_create")
     * @Method("POST")
     * @param Request $request
     *
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function createServer(Request $request) {
        $post = $this->parseJsonContent($request);
        $name = $post->get('name');
        $isDisabled = $post->getBoolean('isDisabled');

        // todo: expand this to validation rules when more datapoints are added
        if ($name !== null && $name !== '' && $isDisabled !== null) {
            $server = new Server;
            $server->setName($name);
            $server->setIsDisabled($isDisabled);
        } else {
            return $this->json(
                ServerErrors::response(ServerErrors::SERVER_MISSING_REQUIRED_PARAMETERS, ['required' => ['name', 'isDisabled']]),
                Response::HTTP_BAD_REQUEST);
        }

        try {
            $em = $this->getDoctrine()->getManager();
            $em->persist($server);
            $em->flush();
        } catch (\Exception $e) {
            return $this->json(
                ServerErrors::response(ServerErrors::SERVER_DUPLICATE_NAME, ['name' => $name], [], [$name]),
                JsonResponse::HTTP_BAD_REQUEST
            );
        }
        return $this->redirectToRoute('api_server_get', ['id' => $server->getId()]);

    }

    /**
     * @Route("/{id}", name="api_server_get")
     * @Method("GET")
     * @param Request         $request
     * @param                 $id
     * @param LoggerInterface $logger
     *
     * @return JsonResponse
     */
    public function getServerInfo(Request $request, $id, LoggerInterface $logger) {
        try {
            if (filter_var($id, FILTER_VALIDATE_INT) === false) {
                return $this->json(ServerErrors::response(ServerErrors::SERVER_ID_INVALID, [], ['id' => $id]), JsonResponse::HTTP_BAD_REQUEST);
            }
            /** @var ServerRepository $serverRepository */
            $serverRepository = $this->getDoctrine()->getRepository(Server::class);
            /** @var Server $server */
            $server = $serverRepository->findOneBy(['id' => $id]);

            if ($server) {
                $server_data = ApiService::getServerGroupData(
                    $server->getName(),
                    $this->getParameter('api_url'),
                    $this->getParameter('api_token'),
                    $logger
                );
                $result = $server->toArray();
                $result['data'] = $server_data;

                return new JsonResponse($result, JsonResponse::HTTP_OK);
            } else {
                return $this->json(
                    ServerErrors::response(ServerErrors::SERVER_ID_NOT_FOUND, [], ['id' => $id], [$id]),
                    JsonResponse::HTTP_NOT_FOUND);
            }
        } catch (\Exception $e) {
            return ResponseHelper::UnknownError();
        }
    }

    /**
     * @Route("/{id}", name="api_server_update")
     * @Method("PUT")
     * @param Request $request
     * @param Server  $server
     *
     * @return Response|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateServerInfo(Request $request, Server $server) {
        $put = $this->parseJsonContent($request);
        try {
            $name = $put->get('name');
            $isDisabled = $put->getBoolean('isDisabled');
            // todo: expand this to validation rules when more datapoints are added
            if ($name !== null && $name !== '' && $isDisabled !== null) {
                $server->setName($name);
                $server->setIsDisabled($isDisabled);
            } else {
                return $this->json(
                    ServerErrors::response(ServerErrors::SERVER_MISSING_REQUIRED_PARAMETERS, ['required' => ['name', 'isDisabled']]),
                    Response::HTTP_BAD_REQUEST);
            }
            try {
                $this->getDoctrine()->getManager()->flush();
            } catch (\Exception $e) {
                return $this->json(
                    ServerErrors::response(ServerErrors::SERVER_DUPLICATE_NAME, ['name' => $name], [], [$name]),
                    JsonResponse::HTTP_BAD_REQUEST);
            }
            return $this->redirectToRoute('api_server_get', ['id' => $server->getId()]);
        } catch (\Exception $e) {
            return ResponseHelper::UnknownError($e);
        }
    }

    /**
     * @Route("/{id}", name="api_server_delete")
     * @Method("DELETE")
     * @param Request $request
     * @param Server  $server
     *
     * @return JsonResponse
     */
    public function deleteServerInfo(Request $request, Server $server) {
        $id = $server->getId();
        $em = $this->getDoctrine()->getManager();
        $em->remove($server);
        $em->flush();
        return $this->json("Server: " . $id . " removed", JsonResponse::HTTP_OK);
    }
}