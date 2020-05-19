<?php

namespace Statusboard\Mbta;

use Psr\Http\Message\ResponseInterface;
use Statusboard\Utility\AbstractMockFetcher;

class MockFetcher extends AbstractMockFetcher implements FetcherInterface {

    const MOCK_DATA_LOCATION = __dir__ . '/../../../data/mocks/MBTA/';
    const DATE_FORMAT = 'D, j M Y H:i:s e';

    const MOCK_DATA_FILE_TRIPS = 'trips_response.json';
    const MOCK_DATA_FILE_SCHEDULE = 'schedule_response.json';

    /**
     * @param string $api_key
     * @param string $trips
     *
     * @return ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public static function getSchedule(string $api_key, string $trips): ResponseInterface {
        return self::buildResponse(
            self::buildHeaders(),
            self::buildSourceFile(self::MOCK_DATA_LOCATION . self::MOCK_DATA_FILE_SCHEDULE)
        );
    }

    /**
     * @param string $api_key
     *
     * @return ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getTrips($api_key): ResponseInterface {
        return self::buildResponse(
            self::buildHeaders(),
            self::buildSourceFile(self::MOCK_DATA_LOCATION . self::MOCK_DATA_FILE_TRIPS)
        );
    }

    protected static function buildHeaders() {
        $now = new \DateTime();
        $modified = new \DateTime('-1 hour');
        return [
            "date"                      => $now->format(self::DATE_FORMAT),
            "content-type"              => "application/vnd.api+json; charset=utf-8",
            "content-length"            => 5348,
            "cache-control"             => "max-age=0, private, must-revalidate",
            "content-encoding"          => "gzip",
            "last-modified"             => $modified->format(self::DATE_FORMAT),
            "server"                    => "Cowboy",
            "strict-transport-security" => "max-age=31536000",
            "vary"                      => "accept-encoding",
            "x-ratelimit-limit"         => 1000,
            "x-ratelimit-remaining"     => 998,
            "x-ratelimit-reset"         => strtotime('+10 minutes'),
            "x-request-id"              => "FhAr30BMIaqT5QsAn_aE",
            "X-Firefox-Spdy"            => "h2",
        ];
    }
}