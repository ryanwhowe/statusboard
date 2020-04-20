<?php

namespace Tests\AppBundle\Controller;

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

        $this->loggedOutClient = static::createClient();
        $this->loggedInClient = static::createClient(
            [],
            [
                'PHP_AUTH_USER' => 'test',
                'PHP_AUTH_PW' => 'test12345',
            ]
        );
    }

    /**
     * Test that a logged out state will return a 401 HTTP_UNAUTHORIZED error
     */
    public function testWeatherLoggedOut() {
        $crawler = $this->loggedOutClient->request('GET', '/api/weather');

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $this->loggedOutClient->getResponse()->getStatusCode(), 'Response Code Error');
    }

    /**
     * Test the logged in state for the weather api and test that the required response elements are present
     */
    public function testWeatherLoggedIn() {
        $crawler = $this->loggedInClient->request('GET', '/api/weather');

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
     * Test the group api response for the required response elements
     */
    public function testGroup() {
        $expected_keys = ['external_ip', 'internal_ip', 'heartbeat', 'time_out'];
        $crawler = $this->loggedInClient->request('GET', '/api/group/vmbox');

        $this->assertEquals(Response::HTTP_OK, $this->loggedInClient->getResponse()->getStatusCode(), 'Response Code Error');
        $response = $this->loggedInClient->getResponse();
        $responseData = json_decode($response->getContent(), true);
        foreach ($responseData as $key => $item) {
            if (is_array($item)) {
                $expected_keys = array_diff($expected_keys, [$item['key']]);
            }
        }
        $this->assertEmpty($expected_keys, 'Missing Expected Key values from response');
    }


    /**
     * Test the ipCheck api response for the required response elements
     */
    public function testIpCheck() {
        $expected_keys = ['external_ip', 'internal_ip', 'heartbeat'];
        $crawler = $this->loggedInClient->request('GET', '/api/ipCheck/vmbox');

        $this->assertEquals(Response::HTTP_OK, $this->loggedInClient->getResponse()->getStatusCode(), 'Response Code Error');
        $response = $this->loggedInClient->getResponse();
        $responseData = json_decode($response->getContent(), true);
        foreach ($expected_keys as $expected_key) {
            $this->assertArrayHasKey($expected_key, $responseData, 'Missing \'' . $expected_key . '\' key from response');
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
