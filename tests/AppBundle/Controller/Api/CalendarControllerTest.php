<?php

namespace Tests\AppBundle\Controller\Api;

use Statusboard\ControllerHelpers\CalendarHelper;
use Statusboard\Utility\Environment;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CalendarControllerTest extends WebTestCase {

    /**
     * @var Client that is not logged in to the site
     */
    protected $loggedInClient;

    /**
     * @var Client that is logged into the site
     */
    protected $loggedOutClient;

    /**
     * Setup the loggedIn and LoggedOut clients
     */
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

    /**
     * @test
     */
    public function getUpcomingInfo() {
        $crawler = $this->loggedInClient->request("GET", "/api/calendar/upcoming");
        $response = $this->loggedInClient->getResponse();
        $body = json_decode($response->getContent(), true);
        $this->assertCount(3, $body);
        $eventTypes = CalendarHelper::getEventTypes();
        foreach ($body as $event) {
            $this->assertTrue(in_array($event['display_name'], $eventTypes), 'Event Display Name');
            $this->assertArrayHasKey('display_name', $event);
            $this->assertArrayHasKey('date', $event);
            $this->assertArrayHasKey('days', $event);
        }
    }

}
