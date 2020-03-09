<?php

namespace Tests\Statusboard\Mbta\Transform;

use PHPUnit\Framework\TestCase;
use Statusboard\Mbta\Transform;

class TransformTest extends TestCase {

    private function getBody(): array {
        return [
            'timeout' => 1234567890
        ];
    }

    private function getResponseBody(): array {
        return [
            Transform::OUTPUT_TIMEOUT => 1234567890
        ];
    }

    /**
     * @test
     */
    public function responseProcessor(){
        $result = Transform::responseProcessor($this->getBody());
        $this->assertEquals($this->getResponseBody(), $result);
    }
}