<?php

namespace Tests\Statusboard\Utility;

use Statusboard\Utility\Environment;
use PHPUnit\Framework\TestCase;

class EnvironmentTest extends TestCase
{

    /**
     * @test
     */
    public function isDevelopment()
    {
        Environment::getType(Environment::TYPE_DEV);
        $this->assertTrue(Environment::isDevelopment());
        Environment::getType(Environment::TYPE_DOCKER_DEV);
        $this->assertTrue(Environment::isDevelopment());
        Environment::getType(Environment::TYPE_PRODUCTION);
        $this->assertFalse(Environment::isDevelopment());
        Environment::getType(Environment::TYPE_TEST);
        $this->assertFalse(Environment::isDevelopment());
    }

    /**
     * @test
     */
    public function getType()
    {
        $env = Environment::getType(Environment::TYPE_DEV);
        $this->assertEquals(Environment::ENV_DEV, $env);
        $env = Environment::getType(Environment::TYPE_DOCKER_DEV);
        $this->assertEquals(Environment::ENV_DOCKER_DEV, $env);
        $env = Environment::getType(Environment::TYPE_PRODUCTION);
        $this->assertEquals(Environment::ENV_PRODUCTION, $env);
        $env = Environment::getType(Environment::TYPE_TEST);
        $this->assertEquals(Environment::ENV_TEST, $env);
    }

    /**
     * @test
     */
    public function isLocalDevelopment()
    {
        Environment::getType(Environment::TYPE_DEV);
        $this->assertFalse(Environment::isLocalDevelopment());
        Environment::getType(Environment::TYPE_DOCKER_DEV);
        $this->assertTrue(Environment::isLocalDevelopment());
        Environment::getType(Environment::TYPE_PRODUCTION);
        $this->assertFalse(Environment::isLocalDevelopment());
        Environment::getType(Environment::TYPE_TEST);
        $this->assertFalse(Environment::isLocalDevelopment());
    }

    public function isTesting(){
        Environment::getType(Environment::TYPE_DEV);
        $this->assertFalse(Environment::isTesting());
        Environment::getType(Environment::TYPE_DOCKER_DEV);
        $this->assertFalse(Environment::isTesting());
        Environment::getType(Environment::TYPE_PRODUCTION);
        $this->assertFalse(Environment::isTesting());
        Environment::getType(Environment::TYPE_TEST);
        $this->assertTrue(Environment::isTesting());
    }
}
