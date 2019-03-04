<?php
/**
 * This file contains the definition for the ApiService class
 *
 * @author Ryan Howe
 * @since  2018-12-05
 */

namespace AppBundle\Cache;

use AppBundle\theAxeRant\Client;
use Symfony\Component\Cache\Simple\FilesystemCache;
use Psr\Log\LoggerInterface;

class ApiService
{

    /**
     * @var string $token The tokenizer to enforce the specific cache search
     */
    private $token = 'ApiService';

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

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * ApiService constructor.
     *
     * @param LoggerInterface $logger
     */
    private function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param int    $tokenType
     * @param string $grouping
     *
     * @return string
     * @throws \Exception
     */
    private function generateCacheToken(int $tokenType, string $grouping){
        switch ($tokenType){
            case self::TOKEN_TYPE_TIME:
                return $this->token . $this->time_token . $grouping;
                break;
            case self::TOKEN_TYPE_DATA:
                return $this->token . $this->data_token . $grouping;
                break;
        }
        throw new \Exception('Invalid Token Type');
    }

    /**
     * @param string $grouping
     * @param string $api_url
     * @param string $api_token
     *
     * @return array
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Exception
     */
    private function getServerGroup(string $grouping, string $api_url, string $api_token){
        $cache = new FilesystemCache();

        /* check to see if there is a cache value and if it is newer than the last passed half hour */
        $time_token = $this->generateCacheToken(self::TOKEN_TYPE_TIME, $grouping);
        $data_token = $this->generateCacheToken(self::TOKEN_TYPE_DATA, $grouping);

        $has_cache = $cache->has($time_token) && $cache->has($data_token);

        if ($has_cache && $cache->get($time_token)) {
            $result = $cache->get($data_token);
            $this->logger->info('Cache result', $result);
            $this->logger->info('Cache Time', ['timestamp' => $cache->get($time_token)]);
        } else {
            $result = Client::create($api_token, $api_url, $grouping)->group();
            $this->logger->info('Api result', $result);
            $cache->set($data_token, $result);
            $cache->set($time_token, time());
        }

        return $result;
    }

    /**
     * @param string $grouping
     * @param string $api_url
     * @param string $api_token
     *
     * @return array
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Exception
     */
    private function getLiveServerGroup(string $grouping, string $api_url, string $api_token){
        $cache = new FilesystemCache();

        $time_token = $this->generateCacheToken(self::TOKEN_TYPE_TIME, $grouping);
        $data_token = $this->generateCacheToken(self::TOKEN_TYPE_DATA, $grouping);

        $result = Client::create($api_token, $api_url, $grouping)->group();
        $this->logger->info('Api result', $result);
        $cache->set($data_token, $result);
        $cache->set($time_token, time());

        return $result;

    }

    static public function getServerGroupData(string $grouping, string $api_url, string $api_token, LoggerInterface $logger){
        $self = new self($logger);
        return $self->getServerGroup($grouping, $api_url, $api_token);
    }

    static public function updateServerCacheData(string $grouping, string $api_url, string $api_token, LoggerInterface $logger){
        $self = new self($logger);
        return $self->getLiveServerGroup($grouping, $api_url, $api_token);
    }
}