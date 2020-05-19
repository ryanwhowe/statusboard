<?php


namespace Statusboard\Weather\Accuweather;


use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Statusboard\Utility\AbstractMockFetcher;
use function GuzzleHttp\Psr7\stream_for;

class MockFetcher extends AbstractMockFetcher implements FetcherInterface {
    const MOCK_DATA_LOCATION = __dir__ . '/../../../../data/mocks/weather/Accuweather/';
    const MOCK_DATA_FILE_5DAY = '5day_response.json';
    const MOCK_DATA_FILE_CURRENT = 'current_response.json';
    const MOCK_DATA_FILE_LOCATION = 'location_response.json';
    const DATE_FORMAT = 'D, j M Y H:i:s e';
    const EXPIRES_5DAY = '+1 hour';
    const EXPIRES_LOCATION = '+1 day';
    const EXPIRES_CURRENT = '+1 hour';

    /**
     * @param string $api_key
     * @param string $location
     *
     * @return ResponseInterface
     * @throws \Exception
     */
    public static function getCurrentConditions(string $api_key, string $location): ResponseInterface {
        return self::buildResponse(
            self::generateMockHeader(self::EXPIRES_CURRENT),
            self::buildSourceFile(self::MOCK_DATA_LOCATION . self::MOCK_DATA_FILE_CURRENT)
        );
    }

    /**
     * @param string $api_key
     * @param string $postal
     *
     * @return ResponseInterface
     * @throws \Exception
     */
    public static function getLocation(string $api_key, string $postal): ResponseInterface {
        return self::buildResponse(
            self::generateMockHeader(self::EXPIRES_LOCATION),
            self::buildSourceFile(self::MOCK_DATA_LOCATION . self::MOCK_DATA_FILE_LOCATION)
        );
    }

    /**
     * @param string $api_key
     * @param string $location
     *
     * @return ResponseInterface
     * @throws \Exception
     */
    public static function getFiveDayForecast(string $api_key, string $location): ResponseInterface {
        return self::buildResponse(
            self::generateMockHeader(self::EXPIRES_5DAY),
            self::buildSourceFile(self::MOCK_DATA_LOCATION . self::MOCK_DATA_FILE_5DAY)
        );
    }

    /**
     * @param string $expires
     *
     * @return array
     * @throws \Exception
     */
    protected static function generateMockHeader(string $expires): array {
        $now = new \DateTime();
        $expires = new \DateTime($expires);
        return [
            "Accept"                       => "*/*",
            "Accept-Encoding"              => "gzip",
            "Accept-Language"              => "en-US",
            "Access-Control-Allow-Headers" => "origin",
            "Access-Control-Allow-Methods" => "GET",
            "Access-Control-Allow-Origin"  => "*",
            "Access-Control-Max-Age"       => 3628800,
            "Cache-Control"                => "public",
            "Connection"                   => "keep-alive",
            "Content-Encoding"             => "gzip",
            "Content-Type"                 => "application/json; charset=utf-8",
            "Date"                         => $now->format(self::DATE_FORMAT),
            "Expires"                      => $expires->format(self::DATE_FORMAT),
            "Host"                         => "api.accuweather.com",
            "Origin"                       => "https://developer.accuweather.com",
            "RateLimit-Limit"              => "50",
            "RateLimit-Remaining"          => "38",
            "Referer"                      => "https://developer.accuweather.com/accuweather-locations-api/apis/get/locations/v1/postalcodes/%7BcountryCode%7D/search",
            "Server"                       => "Microsoft-IIS/10.0",
            "Transfer-Encoding"            => "chunked",
            "User-Agent"                   => "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:76.0) Gecko/20100101 Firefox/76.0",
            "Vary"                         => "Accept-Encoding",
            "X-Forwarded-For"              => "24.91.57.146",
            "X-Forwarded-Port"             => "443",
            "X-Forwarded-Proto"            => "https",
        ];
    }

}