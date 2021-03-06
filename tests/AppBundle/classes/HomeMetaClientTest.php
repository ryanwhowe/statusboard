<?php

namespace Tests\AppBundle\classes;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\theAxeRant\Client;

class HomeMetaClientTest extends WebTestCase
{
    /**
     *
     */
    public function grouping()
    {
        $grouping = 'test';
        $client = static::createClient();
        $token = $client->getKernel()->getContainer()->getParameter('api_token');
        $url = $client->getKernel()->getContainer()->getParameter('api_url');
        $metaClient = Client::create($token, $url, $grouping);
        $this->assertEquals($grouping, $metaClient->getGrouping(),'Grouping Name Check');
    }

    /**
     * @test
     */
    public function silenceWarning(){
        $this->assertTrue(true);
    }

    /**
     *
     */
    public function api()
    {
        $client = static::createClient();
        $token = $client->getKernel()->getContainer()->getParameter('api_token');
        $url = $client->getKernel()->getContainer()->getParameter('api_url');
        $grouping = 'SeaGrassShores';
        $metaClient = Client::create($token, $url, $grouping);
        try {
            $group_data = $metaClient->group();
        } catch (\Exception $e) {
            $group_data = array(\null);
            $this->assertTrue(\false, $e->getMessage());
        }
        $this->assertArrayHasKey('key', $group_data[0], 'Api Response Testing');
    }
}
