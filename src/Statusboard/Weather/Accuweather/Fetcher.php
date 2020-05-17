<?php


namespace Statusboard\Weather\Accuweather;


use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class Fetcher {

    const RESPONSE_TIMEOUT_INTERVAL = 2.0;
    const BASE_URL_CURRENT_CONDITIONS = 'http://dataservice.accuweather.com/currentconditions/v1/';
    const BASE_URL_LOCATION = 'http://dataservice.accuweather.com/locations/v1/postalcodes/search';
    const BASE_URL_FIVE_DAY_FORECAST = 'http://dataservice.accuweather.com/forecasts/v1/daily/5day/';

    /**
     * @param string $api_key
     * @param string $location
     *
     * @return ResponseInterface
     * @throws RequestLimitExceededException
     */
    public static function getCurrentConditions(string $api_key, string $location): ResponseInterface {
        $base_uri = self::BASE_URL_CURRENT_CONDITIONS;
        $uri = $location . '?apikey=' . $api_key;

        return self::getResponse($base_uri, $uri);
    }

    /**
     * @param string $api_key
     * @param string $postal
     *
     * @return ResponseInterface
     * @throws RequestLimitExceededException
     */
    public static function getLocation(string $api_key, string $postal): ResponseInterface {
        $base_uri = self::BASE_URL_LOCATION;
        $uri = '?apikey=' . $api_key . '&q=' . $postal;

        return self::getResponse($base_uri, $uri);
    }

    /**
     * @param string $api_key
     * @param string $location
     *
     * @return ResponseInterface
     * @throws RequestLimitExceededException
     */
    public static function getFiveDayForecast(string $api_key, string $location): ResponseInterface {
        $base_uri = self::BASE_URL_FIVE_DAY_FORECAST;
        $uri = $location . '?apikey=' . $api_key;

        return self::getResponse($base_uri, $uri);
    }

    /**
     * @param string $base_uri
     * @param string $uri
     *
     * @return ResponseInterface
     * @throws RequestLimitExceededException
     */
    private static function getResponse(string $base_uri, string $uri): ResponseInterface {
        $client = new Client([
            'base_uri' => $base_uri,
            'timeout'  => self::RESPONSE_TIMEOUT_INTERVAL,
        ]);
        $request = new Request('get', $uri);
        $response = $client->send($request);
        if ($response->getStatusCode() === Response::HTTP_FORBIDDEN) {
            throw new RequestLimitExceededException();
        }
        return $response;
    }

}