<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ApiControllerTest extends WebTestCase {
    /**
     * @var Symfony\Bundle\FrameworkBundle\Client that is not logged in to the site
     */
    protected $loggedInClient;

    /**
     * @var Symfony\Bundle\FrameworkBundle\Client that is logged into the site
     */
    protected $loggedOutClient;

    protected function setUp() {

        $this->loggedOutClient = static::createClient();
        $this->loggedInClient = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'axe',
            'PHP_AUTH_PW' => 'axe857',
        ));
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
        $response_json = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('headline', $response_json);
        $this->assertArrayHasKey('expires', $response_json);
        $this->assertArrayHasKey('current', $response_json);

        foreach (range(0, 4) as $day) {
            $this->assertArrayHasKey('date', $response_json[$day]);
            $this->assertArrayHasKey('day', $response_json[$day]);
            $this->assertArrayHasKey('hightemp', $response_json[$day]);
            $this->assertArrayHasKey('lowtemp', $response_json[$day]);
            $this->assertArrayHasKey('icons', $response_json[$day]);

            $this->assertArrayHasKey('day', $response_json[$day]['icons']);
            $this->assertArrayHasKey('night', $response_json[$day]['icons']);

            $this->assertArrayHasKey('icontext', $response_json[$day]);

            $this->assertArrayHasKey('day', $response_json[$day]['icontext']);
            $this->assertArrayHasKey('night', $response_json[$day]['icontext']);
        }
    }

    /**
     * Test the group api response for the required response elements
     */
    public function testGroup() {
        $expected_keys = ['external_ip', 'internal_ip', 'heartbeat'];
        $crawler = $this->loggedInClient->request('GET', '/api/group/vmbox');

        $this->assertEquals(Response::HTTP_OK, $this->loggedInClient->getResponse()->getStatusCode(), 'Response Code Error');
        $response = $this->loggedInClient->getResponse();
        $response_json = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('timeout', $response_json, 'Missing timeout key from response');
        foreach ($response_json as $key => $item) {
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
        $response_json = json_decode($response->getContent(), true);
        foreach ($expected_keys as $expected_key) {
            $this->assertArrayHasKey($expected_key, $response_json, 'Missing ' . $expected_key . ' key from response');
        }
    }
}
