<?php

namespace Statusboard\Weather\Accuweather;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use Prophecy\Util\StringUtil;
use Psr\Http\Message\ResponseInterface;
use Statusboard\Utility\AbstractTransform;
use Statusboard\Utility\StringUtility;
use Statusboard\Weather\ApiResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class Transform extends AbstractTransform implements ApiResponseInterface {

    const RESPONSE_LOCATION_KEY = 'Key';
    const RESPONSE_TIMEOUT = 'timeout';

    const RESPONSE_HEADER_EXPIRES = 'Expires';
    const RESPONSE_HEADER_REMAININGLIMIT = 'RateLimit-Remaining';

    const ICON_BASE_DIRECTORY = 'assets/images/weather/accuweather/';

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
        if($dayData['HasPrecipitation']) {
            return StringUtility::buildUniqueString(
                [
                    $dayData['PrecipitationIntensity'],
                    $dayData['PrecipitationType'],
                    $dayData['IconPhrase'],
                ]
            );
        }
        return $dayData['IconPhrase'];
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


    public static function generateIconFileName(int $icon): string{
        return str_pad($icon, 2, "0", STR_PAD_LEFT) . '-s.png';
    }

    /**
     * @param ResponseInterface $response
     * @return string
     */
    public static function getExpiresHeader(ResponseInterface $response): string{
        return self::getSingleHeaderValue($response, self::RESPONSE_HEADER_EXPIRES);
    }

    /**
     * @param ResponseInterface $response
     * @return string
     */
    public static function getRemainingLimitHeader(ResponseInterface $response): string{
        return self::getSingleHeaderValue($response, self::RESPONSE_HEADER_REMAININGLIMIT);
    }

    /**
     * @param ResponseInterface $response
     * @return string
     */
    public static function getLocationKey(ResponseInterface $response): string{
        $body = self::getArrayResponseBody($response);
        return $body[0][self::RESPONSE_LOCATION_KEY];
    }

}