<?php declare(strict_types=1);
/**
 * This file contains the definition for the ApiController class
 *
 * @author Ryan Howe
 * @since  2017-08-28
 */

namespace AppBundle\Controller;

use GuzzleHttp\Exception\ServerException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Statusboard\Mbta\Fetcher as MbtaFetcher;
use Statusboard\Mbta\Transform as Mbta;
use Statusboard\Mbta\TripFilters;
use Statusboard\Weather\Accuweather\Cache as WeatherCache;
use Statusboard\Mbta\Cache as MbtaCache;
use Statusboard\Weather\Accuweather\RequestLimitExceededException;
use Statusboard\Weather\Accuweather\Transform AS Accuweather;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\theAxeRant\Client;
use AppBundle\Cache\ApiService;
use AppBundle\Entity\Server;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerInterface;

class ApiController extends Controller
{

    /**
     * @Route("/api/ipCheck/{grouping}")
     * @param $grouping
     * @param Request $request
     * @return JsonResponse
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
     * @param $grouping
     * @param Request $request
     * @param LoggerInterface $logger
     * @return JsonResponse
     */
    public function group($grouping, Request $request, LoggerInterface $logger){

        $result = ApiService::getServerGroupData(
            $grouping,
            $this->getParameter('api_url'),
            $this->getParameter('api_token'),
            $logger
        );

        $Response = $this->json(\null, JsonResponse::HTTP_OK,
            ['Content-Type' => 'text/json', 'Cache-control' => 'must-revalidate']);

        $Response->setContent(json_encode($result));
        $Response->prepare($request)->setPrivate();

        return $Response;

    }

    /**
     * @Route("/api/weather")
     * @param Request $request
     * @param LoggerInterface $logger
     * @return JsonResponse
     * @throws RequestLimitExceededException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function weather(Request $request, LoggerInterface $logger){

        $cache = new WeatherCache($logger);

        $json_response = Response::HTTP_OK;
        $api_key = $this->getParameter('accuweather_api_key');
        $postal = $this->getParameter('postal_code');

        if($cache->checkCacheTime($cache::CACHE_TYPE_LOCATION)){
            $location = (string)$cache->getCache($cache::CACHE_TYPE_LOCATION);
        } else {
            try{
                $data = Accuweather::getLocation($api_key, $postal);
                $location = $data[Accuweather::RESPONSE_KEY];
                $timeout = $data[Accuweather::RESPONSE_TIMEOUT];
                $cache->updateCache($cache::CACHE_TYPE_LOCATION, $timeout, $location);
            } catch (ServerException $e){
                $json_response = Response::HTTP_FORBIDDEN;
                $location = $this->getParameter('accuweather_api_location');
            }
        }

        if($cache->checkCacheTime($cache::CACHE_TYPE_WEATHER)){
            $body = unserialize($cache->getCache($cache::CACHE_TYPE_WEATHER));
            $body[Accuweather::RESPONSE_TIMEOUT] = $cache->getTimeout($cache::CACHE_TYPE_WEATHER);
        } else {
            try {
                $body = Accuweather::getFiveDayForecast($api_key, $location);
                $body['current'] = Accuweather::getCurrentConditions($api_key, $location);
                $timeout = $body[Accuweather::RESPONSE_TIMEOUT];
                $cache->updateCache($cache::CACHE_TYPE_WEATHER, $timeout, serialize($body));
                $body[Accuweather::RESPONSE_TIMEOUT] = $cache->getTimeout($cache::CACHE_TYPE_WEATHER);
            } catch (ServerException $e){
                $json_response = Response::HTTP_FORBIDDEN;
            }
        }

        $Response = $this->json(\null, $json_response,
            ['Content-Type' => 'text/json', 'Cache-control' => 'must-revalidate']);

        if($json_response === JsonResponse::HTTP_OK) {
            $output = Accuweather::responseProcessor($body);
            $Response->setContent(json_encode($output));
        }

        $Response->prepare($request)->setPrivate();

        return $Response;
    }

    /**
     * @Route("/api/weather/reset")
     * @param Request $request
     * @param LoggerInterface $logger
     * @return JsonResponse
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function weather_reset(Request $request, LoggerInterface $logger){
        $cache = new WeatherCache($logger);
        $cache->deleteCache($cache::CACHE_TYPE_LOCATION);
        $cache->deleteCache($cache::CACHE_TYPE_WEATHER);
        $Response = $this->json(['Weather Cache Cleared'], JsonResponse::HTTP_OK,
            ['Content-Type' => 'text/json', 'Cache-control' => 'must-revalidate']);
        $Response->prepare($request)->setPrivate();
        return $Response;
    }

    /**
     * @Route("/api/mbta")
     * @param Request $request
     * @param LoggerInterface $logger
     * @return JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function mbta(Request $request, LoggerInterface $logger){
        $cache = new MbtaCache($logger);
        $api_key = $this->getParameter('mbta_api_key');
        $json_response = JsonResponse::HTTP_OK;
        if($cache->checkCacheTime($cache::CACHE_TYPE_SCHEDULE)){
            $schedule = unserialize($cache->getCache($cache::CACHE_TYPE_SCHEDULE));
        } else {
            try {
                $trip_filters = [
                    TripFilters::headSignFilter(TripFilters::HEADSIGN_FORGEPARK)
                ];
                $schedule = MbtaFetcher::getSchedule($api_key, $trip_filters);
                $expiration_time = Mbta::getExpirationTime($schedule, time());
                if(empty($schedule)) {
                    $cached = $cache->getCache($cache::CACHE_TYPE_SCHEDULE);
                    if ($cached === null) {
                        $json_response = JsonResponse::HTTP_NO_CONTENT;
                    } else {
                        $schedule = $cached;
                    }
                } else {
                    $cache->updateCache($cache::CACHE_TYPE_SCHEDULE, $expiration_time, serialize($schedule));
                }
            } catch (\Exception $e){
                $schedule = [];
                $json_response = JsonResponse::HTTP_INTERNAL_SERVER_ERROR;
            }
        }

        if($json_response === JsonResponse::HTTP_OK) {
            $Response = $this->json(\null, $json_response,
                ['Content-Type' => 'text/json', 'Cache-control' => 'must-revalidate']);
            $output = Mbta::responseProcessor($schedule);
            $Response->setContent(json_encode($output));
        } elseif($json_response === JsonResponse::HTTP_NO_CONTENT){
            $Response = $this->json(\null, $json_response,
                ['Content-Type' => 'text/json', 'Cache-control' => 'must-revalidate']);
            $Response->setContent(json_encode([]));
        } else {
            $Response = $this->json(\null, $json_response,
                ['Content-Type' => 'text/html', 'Cache-control' => 'must-revalidate']);
            $Response->setContent("<h3>There was an internal error retrieving the schedule from the MBTA server</h3>");
        }

        $Response->prepare($request)->setPrivate();
        return $Response;
    }

    /**
     * @Route("/api/mbta/reset")
     * @param Request $request
     * @param LoggerInterface $logger
     * @return JsonResponse
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function mbta_reset(Request $request, LoggerInterface $logger){
        $cache = new MbtaCache($logger);
        $cache->deleteCache($cache::CACHE_TYPE_SCHEDULE);
        $Response = $this->json(['MBTA Cache Cleared'], JsonResponse::HTTP_OK,
            ['Content-Type' => 'text/json', 'Cache-control' => 'must-revalidate']);
        $Response->prepare($request)->setPrivate();
        return $Response;
    }
}