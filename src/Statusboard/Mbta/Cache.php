<?php


namespace Statusboard\Mbta;

class Cache extends \Statusboard\Utility\Cache
{

    /**
     * @var string $token The tokenizer to enforce the specific cache search
     */
    protected $token = 'MbtaApi';

    CONST CACHE_TYPE_SCHEDULE = 'schedule';

    CONST TIMEOUT_BUFFER = 300;
}