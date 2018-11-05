<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Controller\ApiController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use theAxeRant\HomeMeta\Client;

class ClientTest extends WebTestCase
{
    public function testGrouping()
    {
        $grouping = 'test';
        $metaClient = Client::create(ApiController::AUTH, ApiController::BASE_URL, $grouping);
        $this->assertEquals($grouping, $metaClient->getGrouping(),'Grouping Name Check');
    }

    public function testApi()
    {
        $grouping = 'SeaGrassShores';
        $metaClient = Client::create(ApiController::AUTH, ApiController::BASE_URL, $grouping);
        try {
            $group_data = $metaClient->group();
        } catch (\Exception $e) {
            $group_data = array(\null);
            $this->assertTrue(\false, 'Api Response Failed');
        }
        $this->assertArrayHasKey('key', $group_data[0], 'Api Response Testing');
    }
}
