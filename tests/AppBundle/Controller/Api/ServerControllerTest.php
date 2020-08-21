<?php

namespace Tests\AppBundle\Controller\Api;

use Statusboard\Utility\Environment;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ServerControllerTest extends WebTestCase {

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
    public function getServerInfo() {
        $crawler = $this->loggedInClient->request("GET", "/api/server/1");
        $response = $this->loggedInClient->getResponse();
        $body = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('id', $body);
        $this->assertArrayHasKey('name', $body);
        $this->assertArrayHasKey('isDisabled', $body);
        $this->assertArrayHasKey('data', $body);
    }

    /**
     * @test
     */
    public function addNewServer() {

        $test = [
            'name'       => 'test',
            'isDisabled' => false,
        ];
        $crawler = $this->loggedInClient->request("POST", "/api/server", [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($test));
        $crawler = $this->loggedInClient->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $this->loggedInClient->getResponse()->getStatusCode(), 'Response Code Error');
        $response = $this->loggedInClient->getResponse();
        $body = json_decode($response->getContent(), true);
        $this->assertEquals($test['name'], $body['name']);
        $this->assertEquals($test['isDisabled'], $body['isDisabled']);
        $id = $body['id'];
        $crawler = $this->loggedInClient->request("DELETE", "/api/server/" . $id);
        $this->assertEquals(Response::HTTP_OK, $this->loggedInClient->getResponse()->getStatusCode(), 'Response Code Error');
        $response = $this->loggedInClient->getResponse();
        $body = $response->getContent();
        $this->assertEquals("\"Server: ${id} removed\"", $body, "Delete response message");

    }

    /**
     * @test
     */
    public function updateServerInfo() {
        $update_data = [
            'name'       => 'updateTest',
            'isDisabled' => true,
        ];
        $crawler = $this->loggedInClient->request("GET", "/api/server/1");
        $response = $this->loggedInClient->getResponse();
        $body = json_decode($response->getContent(), true);

        $original_name = $body['name'];
        $original_status = $body['isDisabled'];
        $crawler = $this->loggedInClient->request("PUT", "/api/server/1", [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($update_data));
        $crawler = $this->loggedInClient->followRedirect();

        $response = $this->loggedInClient->getResponse();
        $body = json_decode($response->getContent(), true);

        $this->assertEquals($update_data['name'], $body['name']);
        $this->assertEquals($update_data['isDisabled'], $body['isDisabled']);

        $crawler = $this->loggedInClient->request("PUT", "/api/server/1", [], [], ['CONTENT_TYPE' => 'application/json'], json_encode(['name' => $original_name, 'isDisabled' => $original_status]));
        $crawler = $this->loggedInClient->followRedirect();

        $response = $this->loggedInClient->getResponse();
        $body = json_decode($response->getContent(), true);
        $this->assertEquals($original_name, $body['name']);
        $this->assertEquals($original_status, $body['isDisabled']);

    }

    /**
     * @test
     */
    public function getAllServers() {
        $crawler = $this->loggedInClient->request("GET", "/api/server");

        $this->assertEquals(Response::HTTP_OK, $this->loggedInClient->getResponse()->getStatusCode(), 'Response Code Error');
        $response = $this->loggedInClient->getResponse();
        $responseData = json_decode($response->getContent(), true);
        foreach ($responseData as $server) {
            $this->assertEquals(['id', 'name', 'isDisabled'], array_keys($server));
        }

        $crawler = $this->loggedInClient->request("GET", "/api/server?onlyActive=1");

        $this->assertEquals(Response::HTTP_OK, $this->loggedInClient->getResponse()->getStatusCode(), 'Response Code Error');
        $response = $this->loggedInClient->getResponse();
        $reducedData = json_decode($response->getContent(), true);
        $this->assertTrue((count($reducedData) <= count($responseData)));
        foreach ($reducedData as $server) {
            $this->assertEquals(['id', 'name', 'isDisabled'], array_keys($server));
            $this->assertFalse($server['isDisabled']);
        }
    }
}
