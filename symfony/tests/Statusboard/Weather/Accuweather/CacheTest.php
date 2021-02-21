<?php

namespace Tests\Statusboard\Weather\Accuweather;

use Statusboard\Weather\Accuweather\Cache;
use PHPUnit\Framework\TestCase;

class CacheTest extends TestCase {

    private function getMockLogger(){
        return $this->getMockBuilder('\Monolog\Logger')
                    ->setConstructorArgs(['tests'])
                    ->getMock();
    }

    /**
     * @test
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getCache() {
        $cache = new Cache($this->getMockLogger());
        $test_value = 'Testing Thing';
        $cache->updateCache($cache::CACHE_TYPE_WEATHER, time(), $test_value);
        $result_value = $cache->getCache($cache::CACHE_TYPE_WEATHER);
        $this->assertEquals($test_value, $result_value);
    }

    /**
     * @test
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function checkCacheTime() {
        $cache = new Cache($this->getMockLogger());
        $cache->deleteCache($cache::CACHE_TYPE_WEATHER);

        $result = $cache->checkCacheTime($cache::CACHE_TYPE_WEATHER);
        $this->assertFalse($result, 'Testing that there is no cache present');

        $cache->updateCache($cache::CACHE_TYPE_WEATHER, time(), 'data'); /* class will put time 1 hour into future */
        $result = $cache->checkCacheTime($cache::CACHE_TYPE_WEATHER);
        $this->assertTrue($result, 'Testing that the cache is present and value');

        $cache->deleteCache($cache::CACHE_TYPE_WEATHER);
        $cache->updateCache($cache::CACHE_TYPE_WEATHER, time() - 100 - $cache::TIMEOUT_BUFFER, 'data');
        $result = $cache->checkCacheTime($cache::CACHE_TYPE_WEATHER);
        $this->assertFalse($result, 'Testing that the cache has expired');
        $cache->deleteCache($cache::CACHE_TYPE_WEATHER);
    }

    /**
     * @test
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function updateCache() {
        $cache = new Cache($this->getMockLogger());
        $test_value = 'Old Thing';
        $cache->updateCache($cache::CACHE_TYPE_WEATHER, time(), $test_value);
        $result_value = $cache->getCache($cache::CACHE_TYPE_WEATHER);
        $this->assertEquals($test_value, $result_value);

        $test_value = 'New Thing';
        $cache->updateCache($cache::CACHE_TYPE_WEATHER, time(), $test_value);
        $result_value = $cache->getCache($cache::CACHE_TYPE_WEATHER);
        $this->assertEquals($test_value, $result_value);
        $cache->deleteCache($cache::CACHE_TYPE_WEATHER);
    }

    /**
     * @test
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getTimeout() {
        $cache = new Cache($this->getMockLogger());
        $test_value = time();
        $cache->updateCache($cache::CACHE_TYPE_WEATHER, $test_value, 'Test');
        $result_value = $cache->getTimeout($cache::CACHE_TYPE_WEATHER);
        $this->assertEquals($test_value + $cache::TIMEOUT_BUFFER, $result_value);

        $test_value = time() + 1000;
        $cache->updateCache($cache::CACHE_TYPE_WEATHER, $test_value, 'Test');
        $result_value = $cache->getTimeout($cache::CACHE_TYPE_WEATHER);
        $this->assertEquals($test_value + $cache::TIMEOUT_BUFFER, $result_value);
        $cache->deleteCache($cache::CACHE_TYPE_WEATHER);
    }
}
