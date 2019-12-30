<?php


namespace Statusboard\Weather\Accuweather;


use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Simple\FilesystemCache;

class Cache
{

    /**
     * @var string $token The tokenizer to enforce the specific cache search
     */
    private $token = 'AccuWeatherApi';

    /**
     * @var string
     */
    private $time_token = '_time_';

    /**
     * @var string
     */
    private $data_token = '_data_';

    CONST TOKEN_TYPE_TIME = 1;
    CONST TOKEN_TYPE_DATA = 2;

    CONST CACHE_TYPE_LOCATION = 'location';
    CONST CACHE_TYPE_WEATHER = 'weather';

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var FilesystemCache
     */
    private $cache;

    /**
     * Cache constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->cache = new FilesystemCache();
    }

    /**
     * @param int $tokenType
     * @param string $cacheType
     *
     * @return string
     * @throws \Exception
     */
    private function generateCacheToken(int $tokenType, string $cacheType): string {
        switch ($tokenType){
            case self::TOKEN_TYPE_TIME:
                return $this->token . $this->time_token . $cacheType;
                break;
            case self::TOKEN_TYPE_DATA:
                return $this->token . $this->data_token . $cacheType;
                break;
        }
        throw new \Exception('Invalid Token Type');
    }

    /**
     * @param string $cacheType
     * @return bool
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function checkCacheTime(string $cacheType): bool {
        $token = $this->generateCacheToken(self::TOKEN_TYPE_TIME, $cacheType);

        if($this->cache->has($token)){
            $this->logger->info('Has ' . $token . ' token', [$this->cache->get($token)]);
            return time() >= $this->cache->get($token);
        } else {
            return false;
        }
    }

    /**
     * @param string $cacheType
     * @param int $timeout
     * @param string $value
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function updateCache(string $cacheType, int $timeout, string $value){
        $this->logger->info('Updated '. $cacheType,[]);
        $this->logger->info('timeout', [$timeout]);
        $this->logger->info('value', [$value]);
        $this->cache->set($this->generateCacheToken(self::TOKEN_TYPE_TIME, $cacheType), $timeout + (60 * 60));
        $this->cache->set($this->generateCacheToken(self::TOKEN_TYPE_DATA, $cacheType), $value);
    }

    /**
     * @param string $cacheType
     * @return mixed|null
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getCache(string $cacheType){
        return $this->cache->get($this->generateCacheToken(self::TOKEN_TYPE_DATA, $cacheType));
    }

}