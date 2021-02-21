<?php


namespace Statusboard\Mbta;


use Psr\Http\Message\ResponseInterface;

interface FetcherInterface {

    public static function getSchedule(string $api_key, string $trip_query_param): ResponseInterface;

    public static function getTrips($api_key): ResponseInterface;
}