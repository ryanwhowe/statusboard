<?php
/**
 * This file contains the definition for the ServerWarmer class
 *
 * @author Ryan Howe
 * @since  2018-12-05
 */

namespace AppBundle\Cache;

use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Entity\Server;
use Psr\Log\LoggerInterface;

class ServerWarmer implements CacheWarmerInterface
{

    /**
     * @var ObjectManager $objectManager
     */
    private $objectManager;

    /**
     * @var ContainerInterface $container
     */
    private $container;

    /**
     * @var LoggerInterface $logger
     */
    private $logger;

    /**
     * ServerWarmer constructor.
     *
     * @param ObjectManager      $objectManager
     * @param ContainerInterface $container
     * @param LoggerInterface    $logger
     */
    public function __construct(ObjectManager $objectManager, ContainerInterface $container, LoggerInterface $logger)
    {
        $this->objectManager = $objectManager;
        $this->container = $container;
        $this->logger = $logger;
    }

    /**
     * Checks whether this warmer is optional or not.
     *
     * Optional warmers can be ignored on certain conditions.
     *
     * A warmer should return true if the cache can be
     * generated incrementally and on-demand.
     *
     * @return bool true if the warmer is optional, false otherwise
     */
    public function isOptional()
    {
        return true;
    }

    /**
     * Warms up the cache.
     *
     * @param string $cacheDir The cache directory
     */
    public function warmUp($cacheDir)
    {
        $url = $this->container->getParameter('api_url');
        $token = $this->container->getParameter('api_token');
        $serverRepo = $this->objectManager->getRepository(Server::class);
        $servers = $serverRepo->findAll();
        /**
         * @var Server $server
         */
        foreach ($servers as $server) {
            ApiService::updateServerCacheData(
                $server->getName(),
                $url,
                $token,
                $this->logger
            );
        }
    }
}