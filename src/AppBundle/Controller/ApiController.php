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
use Symfony\Component\Cache\Simple\FilesystemCache;


class ApiController extends Controller {

    /**
     * The internal cache needs to be renewed every half hour since this is when the machines
     * will updated there status'.  This will calculated when the last half hour passed for
     * comparing if the cache should still be used or updated
     *
     * @return int
     */
    private function getCacheTimeout(){
        $time = time();
        //Store how many seconds long our rounding interval is
        //1800 equals one half hour
        $INTERVAL_SECONDS = 1800;

        //Find how far off the prior interval we are
        $offset = ($time % $INTERVAL_SECONDS);

        //Removing this offset takes us to the "round down" half hour
        return $time - $offset;
    }

    /**
     * @Route("/api/{action}/{grouping}", name="ipcheck")
     */
    public function IpcheckAction($action, $grouping, Request $Request)
    {
        $cache = new FilesystemCache();
        $this->container->get('session')->getFlashBag()->all();

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
                        $result = Client::create($this->getParameter('api_token'), $this->getParameter('api_url'), $grouping)->ipCheck();
                    } catch (\Exception $e) {
                        $this->addFlash('error', 'An error occurred getting the internal api data');
                        $this->addFlash('error', $e->getMessage());
                        $trace = $e->getTrace();
                        foreach ($trace as $message) {
                            $this->addFlash('error', $message);
                        }
                    }
                    break;
                case 'group':
                    /* check to see if there is a cache value and if it is newer than the last passed half hour */
                    if($cache->has($grouping.'time') && $cache->get($grouping.'time') >= $this->getCacheTimeout()){
                        $result = $cache->get($grouping.'data');
                    } else {
                        $result = Client::create($this->getParameter('api_token'), $this->getParameter('api_url'), $grouping)->group();
                        $cache->set($grouping.'data', $result);
                        $cache->set($grouping.'time', time());
                    }
                    break;
                case \null:
                    $this->addFlash('error', 'No action specified');
                    break;
                default:
                    $this->addFlash('error', 'Unknown action specified');
                    break;
            }
        }

        /**
         * @var \Symfony\Component\HttpFoundation\Session\Flash\FlashBag
         */
        $flash_bag = $this->container->get('session')->getFlashBag();
        $Response = $this->json(\null, JsonResponse::HTTP_OK, ['Content-Type' => 'text/json', 'Cache-control' => 'must-revalidate']);
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