<?php

namespace Tests\AppBundle\Controller\Api;

use Symfony\Component\HttpFoundation\Response;

class MbtaControllerTest extends ApiBase {

    /**
     * @test
     * Test the Mbta api response for the required response elements
     */
    public function mbta() {
        $expected_keys = ['expires', 'trips'];
        $crawler = $this->loggedInClient->request('GET', '/api/mbta');

        $this->assertEquals(Response::HTTP_OK, $this->loggedInClient->getResponse()->getStatusCode(), 'Response Code Error');
        $response = $this->loggedInClient->getResponse();
        $responseData = json_decode($response->getContent(), true);
        foreach ($expected_keys as $expected_key) {
            $this->assertArrayHasKey($expected_key, $responseData, 'Missing \'' . $expected_key . '\' key from response');
        }
    }

    /**
     * @test
     * Test that a logged out state will return a 401 HTTP_UNAUTHORIZED error
     */
    public function mbtaLoggedOut() {
        $crawler = $this->loggedOutClient->request('GET', '/api/mbta');
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $this->loggedOutClient->getResponse()->getStatusCode(), 'Response Code Error');
    }
}
