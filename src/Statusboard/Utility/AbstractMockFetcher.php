<?php

namespace Statusboard\Utility;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use function GuzzleHttp\Psr7\stream_for;

abstract class AbstractMockFetcher {

    /**
     * @param array  $headers
     * @param string $source
     *
     * @return ResponseInterface
     */
    protected static function buildResponse(array $headers, string $source): ResponseInterface {
        return new Response(
            200,
            $headers,
            stream_for($source)
        );
    }

    /**
     * @param string $filename
     *
     * @return string
     * @throws \Exception
     */
    protected static function buildSourceFile(string $filename) {
        if (!file_exists($filename) && !is_readable($filename)) {
            throw new \Exception("File Not Found");
        }
        return file_get_contents($filename);
    }
}