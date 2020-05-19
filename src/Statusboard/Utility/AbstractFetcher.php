<?php

namespace Statusboard\Utility;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;
use Statusboard\Mbta\Fetcher;

abstract class AbstractFetcher {

    const RESPONSE_TIMEOUT_INTERVAL = 2.0;

    /**
     * @param string $base_uri
     * @param string $uri
     *
     * @return ResponseInterface
     */
    protected static function getResponse(string $base_uri, string $uri): ResponseInterface {
        $client = new Client([
            'base_uri' => $base_uri,
            'timeout'  => Fetcher::RESPONSE_TIMEOUT_INTERVAL,
        ]);
        $request = new Request('get', $uri);
        return $client->send($request);
    }
}