<?php

namespace Statusboard\Mbta;

use Psr\Http\Message\ResponseInterface;
use Statusboard\Utility\AbstractFetcher;

class Fetcher extends AbstractFetcher {

    const BASE_URL = 'https://api-v3.mbta.com';
    const ROUTE = 'CR-Franklin';

    /**
     * @param string $api_key
     * @param string $trip_query_param
     *
     * @return ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getSchedule(string $api_key, string $trip_query_param): ResponseInterface {
        return self::getResponse(self::BASE_URL, '/schedules?api_key=' . $api_key . '&filter[trip]=' . $trip_query_param);
    }

    /**
     * @param string $api_key
     *
     * @return ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getTrips($api_key): ResponseInterface {
        $date = date('Y-m-d');
        return self::getResponse(self::BASE_URL, '/trips?api_key=' . $api_key . '&filter[date]=' . $date . '&filter[route]=' . self::ROUTE);
    }


}