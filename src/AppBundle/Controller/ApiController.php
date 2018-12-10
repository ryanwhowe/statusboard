<?php declare(strict_types=1);
/**
 * This file contains the definition for the ApiController class
 *
 * @author Ryan Howe
 * @since  2017-08-28
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use theAxeRant\HomeMeta\Client;
use AppBundle\Cache\ApiService;

class ApiController extends Controller
{

    /**
     * @Route("/api/{action}/{grouping}")
     */
    public function ApiAction($action, $grouping, Request $Request)
    {
        /**
         * @var array The Result storage to be encoded into json and returned upon successful completion
         */
        $result = [];

        /* if there is no server/grouping given then there is nothing that can be done */
        if (\null === $grouping) {
            $this->addFlash('error', 'No server specified');
        } else {
            switch ($action) {
                case 'ipCheck':
                    try {
                        $result = Client::create($this->getParameter('api_token'),
                            $this->getParameter('api_url'), $grouping)->ipCheck();
                    } catch (\Exception $e) {
                        $this->addFlash('error',
                            'An error occurred getting the internal api data');
                        $this->addFlash('error', $e->getMessage());
                        $trace = $e->getTrace();
                        foreach ($trace as $message) {
                            $this->addFlash('error', $message);
                        }
                    }
                    break;
                case 'group':

                    $result = ApiService::getServerGroupData(
                        $grouping,
                        $this->getParameter('api_url'),
                        $this->getParameter('api_token'),
                        $this->get('logger')
                    );
                    break;
                case \null:
                    $this->addFlash('error', 'No action specified');
                    break;
                default:
                    $this->addFlash('error', 'Unknown action specified');
                    break;
            }
        }

        if (empty($result)) {
            $this->addFlash('error', 'No Data');
        }

        /**
         * @var \Symfony\Component\HttpFoundation\Session\Flash\FlashBag
         */
        $flash_bag = $this->container->get('session')->getFlashBag();

        $Response = $this->json(\null, JsonResponse::HTTP_OK,
            ['Content-Type' => 'text/json', 'Cache-control' => 'must-revalidate']);
        if ($flash_bag->has('error')) {
            $Response->setContent(json_encode($flash_bag->get('error')));
            $Response->setStatusCode(JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        } else {
            $Response->setContent(json_encode($result));
        }

        $Response->prepare($Request)->setPrivate();


        return $Response;
    }

}