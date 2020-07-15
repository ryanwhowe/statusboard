<?php

namespace Tests\AppBundle\Controller;

use Statusboard\Utility\Environment;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ApiControllerTest extends WebTestCase {
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
                'PHP_AUTH_PW' => 'test12345',
            ]
        );
    }

    protected function tearDown()
    {
        Environment::reset();
        parent::tearDown();
    }

    /**
     * Test that a logged out state will return a 401 HTTP_UNAUTHORIZED error
     */
    public function testWeatherLoggedOut() {
        $crawler = $this->loggedOutClient->request('GET', '/api/weather/12345');

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $this->loggedOutClient->getResponse()->getStatusCode(), 'Response Code Error');
    }

    /**
     * Test the logged in state for the weather api and test that the required response elements are present
     */
    public function testWeatherLoggedIn() {

        $crawler = $this->loggedInClient->request('GET', '/api/weather/12345');

        $this->assertEquals(Response::HTTP_OK, $this->loggedInClient->getResponse()->getStatusCode(), 'Response Code Error');
        $response = $this->loggedInClient->getResponse();
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('headline', $responseData);
        $this->assertArrayHasKey('expires', $responseData);
        $this->assertArrayHasKey('current', $responseData);

        foreach (range(0, 4) as $day) {
            $this->assertArrayHasKey('date', $responseData[$day]);
            $this->assertArrayHasKey('day', $responseData[$day]);
            $this->assertArrayHasKey('hightemp', $responseData[$day]);
            $this->assertArrayHasKey('lowtemp', $responseData[$day]);
            $this->assertArrayHasKey('icons', $responseData[$day]);

            $this->assertArrayHasKey('day', $responseData[$day]['icons']);
            $this->assertArrayHasKey('night', $responseData[$day]['icons']);

            $this->assertArrayHasKey('icontext', $responseData[$day]);

            $this->assertArrayHasKey('day', $responseData[$day]['icontext']);
            $this->assertArrayHasKey('night', $responseData[$day]['icontext']);
        }
    }

    /**
     * Test the Mbta api response for the required response elements
     */
    public function testMbta(){
        $expected_keys = ['expires', 'trips'];
        $crawler = $this->loggedInClient->request('GET', '/api/mbta');

        $this->assertEquals(Response::HTTP_OK, $this->loggedInClient->getResponse()->getStatusCode(), 'Response Code Error');
        $response = $this->loggedInClient->getResponse();
        $responseData = json_decode($response->getContent(), true);
        foreach($expected_keys as $expected_key){
            $this->assertArrayHasKey($expected_key, $responseData, 'Missing \'' . $expected_key. '\' key from response');
        }
    }
}
