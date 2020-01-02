<?php


namespace Statusboard\Weather\Accuweather;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Psr\Http\Message\ResponseInterface;
use Statusboard\Weather\ApiResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class Transform implements ApiResponseInterface {

    const RESPONSE_KEY = 'key';
    const RESPONSE_TIMEOUT = 'timeout';

    const RESPONSE_RETRY = 5;
    const RESPONSE_TIMEOUT_INTERVAL = 2.0;

    /**
     * @param string $api_key
     * @param string $postal
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getLocation(string $api_key, string $postal): array {
        $client = new \GuzzleHttp\Client([
            'base_uri' => 'http://dataservice.accuweather.com/locations/v1/postalcodes/search?apikey=okYccfVSHvQKQb0yJkFwx8AUKElmXFRH&q=01757',
            'timeout' => self::RESPONSE_TIMEOUT_INTERVAL
        ]);
        $req = new \GuzzleHttp\Psr7\Request('get', '?apikey=' . $api_key . '&q=' . $postal);
        $resp = $client->send($req);
        $statusCode = $resp->getStatusCode();
        if($statusCode === Response::HTTP_FORBIDDEN){
            return [Response::HTTP_FORBIDDEN];
        } else {
            $timeout = $resp->getHeader('Expires');
            $body = \json_decode((string)$resp->getBody(), true);
        }
        return [
            self::RESPONSE_KEY => $body[0]['Key'],
            self::RESPONSE_TIMEOUT => strtotime($timeout[0])
        ];
    }

    /**
     * @param string $location
     * @param string $api_key
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getFiveDayForecast(string $api_key, string $location): array {
        $client = new \GuzzleHttp\Client([
            'base_uri' => 'http://dataservice.accuweather.com/forecasts/v1/daily/5day/',
            'timeout' => self::RESPONSE_TIMEOUT_INTERVAL
        ]);
        $req = new \GuzzleHttp\Psr7\Request('get', $location . '?apikey=' . $api_key);
        $resp = $client->send($req);
        $statusCode = $resp->getStatusCode();
        if($statusCode === Response::HTTP_FORBIDDEN){
            return [Response::HTTP_FORBIDDEN];
        } else {
            $timeout = $resp->getHeader('Expires');
            $response = \json_decode((string)$resp->getBody(), true);
            $response[self::RESPONSE_TIMEOUT] = strtotime($timeout[0]);
        }
        return $response;
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
                'icontext' => [
                    'day' => self::extractIconPhrase($body['DailyForecasts'][$day]['Day']),
                    'night' => self::extractIconPhrase($body['DailyForecasts'][$day]['Night'])
                ]
            ];
        }
        $output['headline'] = $body['Headline']['Text'];
        $output['expires'] = $body[self::RESPONSE_TIMEOUT];
        return $output;
    }

    /**
     * @param int $icon
     * @return string
     */
    public static function generateIconImageUrl(int $icon): string {
        return 'https://developer.accuweather.com/sites/default/files/' . str_pad($icon, 2, "0", STR_PAD_LEFT) . '-s.png';
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

}