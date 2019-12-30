<?php declare(strict_types=1);
/**
 * This file contains the definition for the ApiController class
 *
 * @author Ryan Howe
 * @since  2017-08-28
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Statusboard\Weather\Accuweather\Cache;
use Statusboard\Weather\Accuweather\Transform AS Accuweather;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Cache\Simple\FilesystemCache;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\theAxeRant\Client;
use AppBundle\Cache\ApiService;
use AppBundle\Entity\Server;

class ApiController extends Controller
{

    /**
     * @Route("/api/ipCheck/{grouping}")
     */
    public function ipCheck($grouping, Request $request){

        /**
         * @var array The Result storage to be encoded into json and returned upon successful completion
         */
        $result = [];

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

        $Response->prepare($request)->setPrivate();

        return $Response;
    }

    /**
     * @Route("/api/group/{grouping}")
     */
    public function group($grouping, Request $request){

        $result = ApiService::getServerGroupData(
            $grouping,
            $this->getParameter('api_url'),
            $this->getParameter('api_token'),
            $this->get('logger')
        );

        $Response = $this->json(\null, JsonResponse::HTTP_OK,
            ['Content-Type' => 'text/json', 'Cache-control' => 'must-revalidate']);

        $Response->setContent(json_encode($result));
        $Response->prepare($request)->setPrivate();

        return $Response;

    }

    /**
     * @Route("/api/weather")
     */
    public function weather(Request $request){

        $cache = new Cache($this->get('logger'));

        $api_key = $this->getParameter('accuweather_api_key');
        $postal = $this->getParameter('postal_code');

        if($cache->checkCacheTime($cache::CACHE_TYPE_LOCATION)){
            $location = (string)$cache->getCache($cache::CACHE_TYPE_LOCATION);
        } else {
            $data = Accuweather::getLocation($api_key, $postal);
            $location = (string)$data[Accuweather::RESPONSE_KEY];
            $timeout = $data[Accuweather::RESPONSE_TIMEOUT];
            $cache->updateCache($cache::CACHE_TYPE_LOCATION, $timeout, $location);
        }

        if($cache->checkCacheTime($cache::CACHE_TYPE_WEATHER)){
            $body = unserialize($cache->getCache($cache::CACHE_TYPE_WEATHER));
        } else {
            $data = Accuweather::getFiveDayForecast($api_key, $location);
            $body = $data;
            $timeout = $data[Accuweather::RESPONSE_TIMEOUT];
            $cache->updateCache($cache::CACHE_TYPE_WEATHER, $timeout, serialize( $body));
        }

        $output = Accuweather::responseProcessor($body);
        $Response = $this->json(\null, JsonResponse::HTTP_OK,
            ['Content-Type' => 'text/json', 'Cache-control' => 'must-revalidate']);

        $Response->setContent(json_encode($output));

        $Response->prepare($request)->setPrivate();

        return $Response;
    }
}