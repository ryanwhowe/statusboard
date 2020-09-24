<?php

namespace Tests\AppBundle\Controller\Api;

use Symfony\Component\HttpFoundation\Response;

class WeatherControllerTest extends ApiBase {

    /**
     * @test
     * Test that a logged out state will return a 401 HTTP_UNAUTHORIZED error
     */
    public function weatherLoggedOut() {
        $crawler = $this->loggedOutClient->request('GET', '/api/weather/12345');
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $this->loggedOutClient->getResponse()->getStatusCode(), 'Response Code Error');
    }

    /**
     * @test
     * Test the logged in state for the weather api and test that the required response elements are present
     */
    public function weatherLoggedIn() {

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
}
