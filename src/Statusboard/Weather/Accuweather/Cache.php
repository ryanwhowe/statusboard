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

    CONST TIMEOUT_BUFFER = 60 * 60;
}
