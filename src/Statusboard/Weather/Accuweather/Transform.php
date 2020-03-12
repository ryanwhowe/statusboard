<?php


namespace Statusboard\Weather\Accuweather;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;
use Statusboard\Weather\ApiResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class Transform implements ApiResponseInterface {

    const RESPONSE_KEY = 'key';
    const RESPONSE_TIMEOUT = 'timeout';

    const RESPONSE_RETRY = 5;
    const RESPONSE_TIMEOUT_INTERVAL = 2.0;
    const ICON_BASE_DIRECTORY = 'assets/images/weather/accuweather/';
    const BASE_URL_CURRENT_CONDITIONS = 'http://dataservice.accuweather.com/currentconditions/v1/';
    const BASE_URL_LOCATION = 'http://dataservice.accuweather.com/locations/v1/postalcodes/search';
    const BASE_URL_FIVE_DAY_FORECAST = 'http://dataservice.accuweather.com/forecasts/v1/daily/5day/';

    /**
     * @param string $api_key
     * @param string $location
     * @return array
     * @throws RequestLimitExceededException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getCurrentConditions(string $api_key, string $location): array {
        $base_uri = self::BASE_URL_CURRENT_CONDITIONS;
        $uri = $location . '?apikey=' . $api_key;

        return self::getResponse($base_uri, $uri);
    }

    /**
     * @param string $api_key
     * @param string $postal
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws RequestLimitExceededException
     */
    public static function getLocation(string $api_key, string $postal): array {
        $base_uri = self::BASE_URL_LOCATION;
        $uri = '?apikey=' . $api_key . '&q=' . $postal;

        $body = self::getResponse($base_uri, $uri);

        return [
            self::RESPONSE_KEY => $body[0]['Key'],
            self::RESPONSE_TIMEOUT => $body[self::RESPONSE_TIMEOUT]
        ];
    }

    /**
     * @param string $location
     * @param string $api_key
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws RequestLimitExceededException
     */
    public static function getFiveDayForecast(string $api_key, string $location): array {
        $base_uri = self::BASE_URL_FIVE_DAY_FORECAST;
        $uri = $location . '?apikey=' . $api_key;

        return self::getResponse($base_uri, $uri);
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
            $timeout = $response->getHeader('Expires');
            $return = \json_decode((string)$response->getBody(), true);
            $return[self::RESPONSE_TIMEOUT] = strtotime($timeout[0]);
        }
        return $return;
    }

    /**
     * @param array $body
     * @return array
     */
    public static function responseProcessor(array $body): array {
        $output = [];
        foreach (range(0, 4) as $day) {
            $output[$day] = [
                'date' => $body['DailyForecasts'][$day]['EpochDate'],
                'day' => date('l', $body['DailyForecasts'][$day]['EpochDate']),
                'hightemp' => self::formatNumber($body['DailyForecasts'][$day]['Temperature']['Maximum']['Value']),
                'lowtemp' => self::formatNumber($body['DailyForecasts'][$day]['Temperature']['Minimum']['Value']),
                'icons' => [
                    'day' => self::generateIconImageUrl($body['DailyForecasts'][$day]['Day']['Icon']),
                    'night' => self::generateIconImageUrl($body['DailyForecasts'][$day]['Night']['Icon']),
                ],
                'weather-icons' => [
                    'day' => TranslateIconsToWeatherIcon::map($body['DailyForecasts'][$day]['Day']['Icon']),
                    'night' => TranslateIconsToWeatherIcon::map($body['DailyForecasts'][$day]['Night']['Icon']),
                ],
                'icontext' => [
                    'day' => self::extractIconPhrase($body['DailyForecasts'][$day]['Day']),
                    'night' => self::extractIconPhrase($body['DailyForecasts'][$day]['Night'])
                ]
            ];
        }
        $output['headline'] = $body['Headline']['Text'];
        $output['expires'] = $body[self::RESPONSE_TIMEOUT];
        $output['current'] = [
            'condition' => $body['current'][0]['WeatherText'],
            'temp' => self::formatNumber($body['current'][0]['Temperature']['Imperial']['Value']),
            'link' => $body['current'][0]['Link'],
            'icon' => self::generateIconImageUrl($body['current'][0]['WeatherIcon']),
            'weather-icon' => TranslateIconsToWeatherIcon::map($body['current'][0]['WeatherIcon'])
        ];
        return $output;
    }

    /**
     * @param int $icon
     * @return string
     */
    public static function generateIconImageUrl(int $icon): string {
        return self::ICON_BASE_DIRECTORY . self::generateIconFileName($icon);
    }

    /**
     * @param array $dayData
     * @return string
     */
    public static function extractIconPhrase(array $dayData): string {
        return $dayData['HasPrecipitation'] ? $dayData['PrecipitationIntensity'] . ' ' . $dayData['PrecipitationType'] . ' ' . $dayData['IconPhrase'] : $dayData['IconPhrase'];
    }

    /**
     * @param int $number
     * @param int $decimals
     * @param string $dec_point
     * @param string $thousands_sep
     * @return int
     */
    public static function formatNumber(int $number, int $decimals = 0, string $dec_point = '.', string $thousands_sep = ','): int {
        return (int)number_format($number, $decimals, $dec_point, $thousands_sep);
    }

    private static function getHandlerStack(){
        $handler = HandlerStack::create();
        $handler->push(Middleware::retry(
            function($retries, $request, ResponseInterface $response = null, $exception = null){
                if($retries > self::RESPONSE_RETRY) {
                    return false;
                }
                if($response) {
                    if($response->getStatusCode() === 200)
                        return false;
                }
                return true;
            },
            function($retries){
                return $retries * 1000;
            }
        ));
        $handler->push(Middleware::mapRequest(
            function(ResponseInterface $response){
                if($response !== null){
                    $statusCode = $response->getStatusCode();
                    if($statusCode === 403){
                        return $response->withStatus(200);
                    }
                }
                return $response;
            }
        ));
        return $handler;
    }

    public static function generateIconFileName(int $icon): string{
        return str_pad($icon, 2, "0", STR_PAD_LEFT) . '-s.png';
    }

}