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
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ServerController extends Controller {

    /**
     * @Route("/api/server")
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
     * @Route("/api/server")
     * @Method("POST")
     * @param Request $request
     *
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function addNewServer(Request $request) {
        $name = $request->request->get('name');
        $isDisabled = $request->request->getBoolean('isDisabled');

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
        return $this->redirectToRoute('app_api_server_getserverinfo', ['id' => $server->getId()]);

    }

    /**
     * @Route("/api/server/{id}")
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
     * @Route("/api/server/{id}")
     * @Method("PUT")
     * @param Request $request
     * @param Server  $server
     *
     * @return Response|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateServerInfo(Request $request, Server $server) {
        try {
            $name = $request->request->get('name');
            $isDisabled = $request->request->getBoolean('isDisabled');
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
            return $this->redirectToRoute('app_api_server_getserverinfo', ['id' => $server->getId()]);
        } catch (\Exception $e) {
            return ResponseHelper::UnknownError($e);
        }
    }

    /**
     * @Route("/api/server/{id}")
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