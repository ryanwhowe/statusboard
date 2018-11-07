<?php declare(strict_types=1);

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TimeSheetControllerTest extends WebTestCase
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
        $this->loggedInClient = static::createClient([], [
            'PHP_AUTH_USER' => 'axe',
            'PHP_AUTH_PW'   => 'axe857',
        ]);
    }

    public function testTimesheet()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/timeSheet');
    }

    public function testTimesheetupdate()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/utility/timeSheetUpdate');
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
