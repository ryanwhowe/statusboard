<?php

namespace Statusboard\ControllerHelpers;

use GuzzleHttp\Exception\ServerException;
use Statusboard\Mbta\Cache as MbtaCache;
use Statusboard\Mbta\Transform as Mbta;
use Statusboard\Mbta\TripFilters;
use Statusboard\Mbta\FetcherInterface as MbtaFetcherInterface;
use Statusboard\Weather\Accuweather\Cache as AccuweatherCache;
use Statusboard\Weather\Accuweather\FetcherInterface as AccuweatherFetcherInterface;
use Statusboard\Weather\Accuweather\RequestLimitExceededException;
use Statusboard\Weather\Accuweather\Transform as Accuweather;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiHelper {

    /**
     * @param AccuweatherCache            $cache
     *
     * @param AccuweatherFetcherInterface $fetcher
     * @param string                      $api_key
     * @param string                      $postal
     * @param string                      $default_location
     *
     * @return array
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public static function getAccuweatherData(AccuweatherCache $cache, AccuweatherFetcherInterface $fetcher, string $api_key, string $postal, string $default_location): array {
        $json_response = Response::HTTP_OK;

        $request_limit = (int)$cache->getCacheIfSet($cache::CACHE_TYPE_REQUESTLIMIT, '50');

        if ($cache->checkCacheTime($cache::CACHE_TYPE_LOCATION)) {
            $location = (string)$cache->getCache($cache::CACHE_TYPE_LOCATION);
        } else {
            try {
                $location_response = $fetcher::getLocation($api_key, $postal);
                $location = Accuweather::getLocationKey($location_response);
                $timeout = strtotime(Accuweather::getExpiresHeader($location_response));
                $request_limit = (int)Accuweather::getRemainingLimitHeader($location_response);
                $cache->updateCache($cache::CACHE_TYPE_LOCATION, $timeout, $location);
                $cache->setRequestLimit($request_limit);
            } catch (ServerException $e) {
                $json_response = Response::HTTP_FORBIDDEN;
                $location = $default_location;
            } catch (RequestLimitExceededException $e) {
                $json_response = Response::HTTP_FORBIDDEN;
                $location = $default_location;
            }
        }

        if (
            $cache->hasData($cache::CACHE_TYPE_WEATHER) &&
            ($cache->checkCacheTime($cache::CACHE_TYPE_WEATHER) || $request_limit < 2)
        ) {
            $body = unserialize($cache->getCache($cache::CACHE_TYPE_WEATHER));
            $body[Accuweather::RESPONSE_TIMEOUT] = $cache->getTimeout($cache::CACHE_TYPE_WEATHER);
        } else {
            try {
                $fiveday_response = $fetcher::getFiveDayForecast($api_key, $location);
                $body = Accuweather::getArrayResponseBody($fiveday_response);
                $current_response = $fetcher::getCurrentConditions($api_key, $location);
                $body['current'] = Accuweather::getArrayResponseBody($current_response);
                $timeout = strtotime(Accuweather::getExpiresHeader($fiveday_response));
                $request_limit = (int)Accuweather::getRemainingLimitHeader($current_response);
                $body['request_limit'] = $request_limit;
                $cache->updateCache($cache::CACHE_TYPE_WEATHER, $timeout, serialize($body));
                $cache->setRequestLimit($request_limit);
                $body[Accuweather::RESPONSE_TIMEOUT] = $cache->getTimeout($cache::CACHE_TYPE_WEATHER);
            } catch (ServerException $e) {
                $json_response = Response::HTTP_FORBIDDEN;
            } catch (RequestLimitExceededException $e) {
                $json_response = Response::HTTP_FORBIDDEN;
            }
        }
        return [$json_response, $body];
    }

    /**
     * @param MbtaCache            $cache
     * @param MbtaFetcherInterface $fetcher
     * @param string               $api_key
     *
     * @return array
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public static function getMbtaData(MbtaCache $cache, MbtaFetcherInterface $fetcher, string $api_key): array {

        $json_response = JsonResponse::HTTP_OK;

        if ($cache->checkCacheTime($cache::CACHE_TYPE_SCHEDULE)) {
            $schedule = unserialize($cache->getCache($cache::CACHE_TYPE_SCHEDULE));
        } else {
            try {
                $trip_filters = [
                    TripFilters::headSignFilter(TripFilters::HEADSIGN_FORGEPARK),
                ];
                $trips = $fetcher::getTrips($api_key);
                $filtered_trips = Mbta::generateTripsParameter($trips, $trip_filters);
                $schedule_response = $fetcher::getSchedule($api_key, $filtered_trips);
                $expiration_time = Mbta::getExpirationTime($schedule_response, time());
                $schedule = Mbta::getArrayResponseBody($schedule_response);
                if (empty($schedule)) {
                    $cached = $cache->getCache($cache::CACHE_TYPE_SCHEDULE);
                    if ($cached === null) {
                        $json_response = JsonResponse::HTTP_NO_CONTENT;
                    } else {
                        $schedule = $cached;
                    }
                } else {
                    $cache->updateCache($cache::CACHE_TYPE_SCHEDULE, $expiration_time, serialize($schedule));
                }
            } catch (\Exception $e) {
                $schedule = [];
                $json_response = JsonResponse::HTTP_INTERNAL_SERVER_ERROR;
            }
        }
        return [$schedule, $json_response];
    }

}