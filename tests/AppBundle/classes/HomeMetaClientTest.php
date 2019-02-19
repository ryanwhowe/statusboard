<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Controller\ApiController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\theAxeRant\Client;

class ClientTest extends WebTestCase
{
    public function testGrouping()
    {
        $grouping = 'test';
        $client = static::createClient();
        $token = $client->getKernel()->getContainer()->getParameter('api_token');
        $url = $client->getKernel()->getContainer()->getParameter('api_url');
        $metaClient = Client::create($token, $url, $grouping);
        $this->assertEquals($grouping, $metaClient->getGrouping(),'Grouping Name Check');
    }

    public function testApi()
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
