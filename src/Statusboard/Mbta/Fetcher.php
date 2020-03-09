<?php

namespace Statusboard\Mbta;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\HttpFoundation\Response;
use Statusboard\Weather\Accuweather\RequestLimitExceededException;

class Fetcher {
    const CACHE_TIME = 600; //seconds
    const BASE_URL = 'https://api-v3.mbta.com';
    const LINE = 'line-Franklin';
    const ROUTE = 'CR-Franklin';
    const ROUTE_PATTERN = 'CR-Franklin-0-0';
    const TRIP_OUTBOUND = 0;
    const TRIP_INBOUND = 1;
    const RESPONSE_RETRY = 5;
    const RESPONSE_TIMEOUT_INTERVAL = 2.0;

    /**
     * @return array|int
     * @throws RequestLimitExceededException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getSchedule() {
        $trips = self::getTrips();
        if($trips) {
            return self::getResponse(self::BASE_URL, '/schedules?filter[trip]=' . $trips);
        } else {
            return [];
        }
    }

    /**
     * @return string
     * @throws RequestLimitExceededException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getTrips(){
        $date = date('Y-m-d');
        $content = self::getResponse(self::BASE_URL,'/trips?filter[date]=' . $date . '&filter[route]=' . self::ROUTE);
        $filtered = array_filter($content['data'], function ($value) {
            /* filter out the inbound trains OR the trains that are on a different route than the one of interest since they do not have the stop needed */
            if ($value['attributes']['direction_id'] === self::TRIP_INBOUND || $value['relationships']['route_pattern']['data']['id'] !== self::ROUTE_PATTERN) {
                return false;
            }
            return true;
        });

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
    private static function getResponse(string $base_uri, string $uri){
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