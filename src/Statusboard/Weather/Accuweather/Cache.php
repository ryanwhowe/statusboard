<?php

namespace Statusboard\Weather\Accuweather;

class Cache extends \Statusboard\Utility\Cache
{

    /**
     * @var string $token The tokenizer to enforce the specific cache search
     */
    protected $token = 'AccuWeatherApi';

    CONST CACHE_TYPE_LOCATION = 'location';
    CONST CACHE_TYPE_WEATHER = 'weather';
    CONST CACHE_TYPE_REQUESTLIMIT = 'limit';

    CONST TIMEOUT_BUFFER = 60 * 60;

    /**
     * @param int $request_limit
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function setRequestLimit(int $request_limit){
        $this->updateCache(self::CACHE_TYPE_REQUESTLIMIT, strtotime('+24 hours'), (string)$request_limit);
    }
}
