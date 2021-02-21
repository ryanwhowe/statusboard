<?php

namespace Tests\AppBundle\Controller\Api;

use Statusboard\Utility\Environment;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiBase extends WebTestCase {

    /**
     * @var Client that is not logged in to the site
     */
    protected $loggedInClient;

    /**
     * @var Client that is logged into the site
     */
    protected $loggedOutClient;

    protected function setUp() {
        parent::setUp();
        Environment::getType(Environment::TYPE_TEST);
        $this->loggedOutClient = static::createClient();
        $this->loggedInClient = static::createClient(
            [],
            [
                'PHP_AUTH_USER' => 'test',
                'PHP_AUTH_PW'   => 'test12345',
            ]
        );
    }

    protected function tearDown() {
        Environment::reset();
        parent::tearDown();
    }

}