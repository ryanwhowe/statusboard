<?php

namespace Statusboard\Mbta;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\HttpFoundation\Response;
use Statusboard\Weather\Accuweather\RequestLimitExceededException;

class Fetcher {

    const BASE_URL = 'https://api-v3.mbta.com';
    const LINE = 'line-Franklin';
    const ROUTE = 'CR-Franklin';
    const ROUTE_PATTERN = 'CR-Franklin-0-0';
    const TRIP_OUTBOUND = 0;
    const TRIP_INBOUND = 1;
    const RESPONSE_RETRY = 5;
    const RESPONSE_TIMEOUT_INTERVAL = 2.0;

    /**
     * @param array $trip_filters
     * @return array|int
     * @throws RequestLimitExceededException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getSchedule($api_key, array $trip_filters) {
        $trip_data = self::getTrips($api_key);
        $trips = self::processTrips($trip_data, $trip_filters);
        if($trips) {
            return self::getResponse(self::BASE_URL, '/schedules?api_key=' . $api_key . '&filter[trip]=' . $trips);
        } else {
            return [];
        }
    }

    /**
     * @param string $api_key
     * @return array
     * @throws RequestLimitExceededException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getTrips($api_key){
        $date = date('Y-m-d');
        return self::getResponse(self::BASE_URL,'/trips?api_key=' . $api_key . '&filter[date]=' . $date . '&filter[route]=' . self::ROUTE);
    }

    /**
     * @param array $content
     * @param array $filters
     * @return string
     */
    public static function processTrips(array $content, array $filters){
        $filtered = $content['data'];
        foreach ($filters as $filter){
            if(is_callable($filter)){
                $filtered =  array_values(array_filter($filtered, $filter));
            }
        }

        $trips = [];

        foreach ($filtered as $test) {
            $trips[] = $test['id'];
        }
        return implode(',', $trips);
    }

    /**
     * @param string $base_uri
     * @param string $uri
     * @return int|array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws RequestLimitExceededException
     */
    public static function getResponse(string $base_uri, string $uri){
        $client = new Client([
            'base_uri' => $base_uri,
            'timeout' => self::RESPONSE_TIMEOUT_INTERVAL
        ]);
        $request = new Request('get', $uri);
        $response = $client->send($request);
        $statusCode = $response->getStatusCode();
        if($statusCode === Response::HTTP_FORBIDDEN){
            throw new RequestLimitExceededException();
        } else {
            $return = \json_decode((string)$response->getBody(), true);
        }
        return $return;
    }

}