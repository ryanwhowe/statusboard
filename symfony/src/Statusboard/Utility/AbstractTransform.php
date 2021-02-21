<?php

namespace Statusboard\Utility;

use Psr\Http\Message\ResponseInterface;

abstract class AbstractTransform {

    /**
     * @param ResponseInterface $response
     *
     * @return string
     */
    public static function getResponseBody(ResponseInterface $response): string {
        return (string)$response->getBody();
    }

    /**
     * @param ResponseInterface $response
     *
     * @return array
     */
    public static function getArrayResponseBody(ResponseInterface $response): array {
        return json_decode(self::getResponseBody($response), true);
    }

    /**
     * @param ResponseInterface $response
     * @param string            $header
     *
     * @return string
     */
    protected static function getSingleHeaderValue(ResponseInterface $response, string $header): string {
        $headers = $response->getHeader($header);
        return $headers[0];
    }
}