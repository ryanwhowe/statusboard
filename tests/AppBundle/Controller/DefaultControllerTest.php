<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class DefaultControllerTest extends WebTestCase
{
    /**
     * @var Client that is not logged in to the site
     */
    protected $loggedInClient;

    /**
     * @var Client that is logged into the site
     */
    protected $loggedOutClient;

    protected function setUp() {

        $this->loggedOutClient = static::createClient();
        $this->loggedInClient = static::createClient([], [
            'PHP_AUTH_USER' => 'test',
            'PHP_AUTH_PW'   => 'test12345',
        ]);
    }

    /**
     * @test
     * Test that a logged out state will return a 401 HTTP_UNAUTHORIZED error
     */
    public function indexLoggedOut() {
        $crawler = $this->loggedOutClient->request('GET', '/');

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $this->loggedOutClient->getResponse()->getStatusCode(), 'Response Code Error');
    }

    /**
     * @test
     * Test the logged in state as well as that the expected content is rendered
     */
    public function indexLoggedIn() {
        $crawler = $this->loggedInClient->request('GET', '/');

        $this->assertEquals(Response::HTTP_OK, $this->loggedInClient->getResponse()->getStatusCode(), 'Response Code Error');
        $this->assertContains('Dashboard', $crawler->filter('.page-header')->text());
    }

    /**
     * @test
     * Test the logged in time sheet page header
     */
    public function timeSheetLoggedIn() {
        $crawler = $this->loggedInClient->request('GET', '/timeSheet');

        $this->assertEquals(Response::HTTP_OK, $this->loggedInClient->getResponse()->getStatusCode(), 'Response Code Error');
        $this->assertContains('Time Sheet', $crawler->filter('.page-header')->text());
    }
}
