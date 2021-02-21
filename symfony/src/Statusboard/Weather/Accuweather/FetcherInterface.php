<?php

namespace Statusboard\Weather\Accuweather;

use Psr\Http\Message\ResponseInterface;

interface FetcherInterface {

    /**
     * Get the Accuweather current conditions forecast response
     *
     * @param string $api_key
     * @param string $location
     *
     * @return ResponseInterface
     * @throws RequestLimitExceededException
     */
    public static function getCurrentConditions(string $api_key, string $location): ResponseInterface;

    /**
     * Get the Accuweather translated locationID response from the provided postal code
     *
     * @param string $api_key
     * @param string $postal
     *
     * @return ResponseInterface
     * @throws RequestLimitExceededException
     */
    public static function getLocation(string $api_key, string $postal): ResponseInterface;


    /**
     * Get the Accuweather 5 day forecast response
     *
     * @param string $api_key
     * @param string $location
     *
     * @return ResponseInterface
     * @throws RequestLimitExceededException
     */
    public static function getFiveDayForecast(string $api_key, string $location): ResponseInterface;
}