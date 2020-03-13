<?php

namespace Statusboard\Lottery\Magayo;

class Cache extends \Statusboard\Utility\Cache
{

    /**
     * @var string $token The tokenizer to enforce the specific cache search
     */
    protected $token = 'MagayoLotteryApi';

    CONST TIMEOUT_BUFFER = 60 * 60;
}
