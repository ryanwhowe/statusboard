<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class DefaultControllerTest extends WebTestCase
{
    /**
     * @var Symfony\Bundle\FrameworkBundle\Client that is not logged in to the site
     */
    protected $loggedInClient;

    /**
     * @var Symfony\Bundle\FrameworkBundle\Client that is logged into the site
     */
    protected $loggedOutClient;

    protected function setUp(){

        $this->loggedOutClient = static::createClient();
        $this->loggedInClient = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'test',
            'PHP_AUTH_PW' => 'test12345',
        ));
    }
    /**
     * Test that a logged out state will return a 401 HTTP_UNAUTHORIZED error
     */
    public function testIndexLoggedOut()
    {
        $crawler = $this->loggedOutClient->request('GET', '/');

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $this->loggedOutClient->getResponse()->getStatusCode(),'Response Code Error');
    }

    /**
     * Test the logged in state as well as that the expected content is rendered
     */
    public function testIndexLoggedIn(){
        $crawler = $this->loggedInClient->request('GET', '/');

        $this->assertEquals(Response::HTTP_OK, $this->loggedInClient->getResponse()->getStatusCode(),'Response Code Error');
        $this->assertContains('Dashboard', $crawler->filter('.page-header')->text());
    }

    /**
     * Test the logged in calendar page header
     */
    public function testCalendarLoggedIn(){
        $crawler = $this->loggedInClient->request('GET', '/calendar');

        $this->assertEquals(Response::HTTP_OK, $this->loggedInClient->getResponse()->getStatusCode(),'Response Code Error');
        $this->assertContains('Calendar', $crawler->filter('.page-header')->text());
        $this->assertEquals(1, $crawler->filter('.jquery_ui_datepicker')->count());
    }

    /**
     * Test the logged in time sheet page header
     */
    public function testTimeSheetLoggedIn(){
        $crawler = $this->loggedInClient->request('GET', '/timeSheet');

        $this->assertEquals(Response::HTTP_OK, $this->loggedInClient->getResponse()->getStatusCode(),'Response Code Error');
        $this->assertContains('Time Sheet', $crawler->filter('.page-header')->text());
    }
}
