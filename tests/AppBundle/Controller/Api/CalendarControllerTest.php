<?php

namespace Tests\AppBundle\Controller\Api;

use AppBundle\Entity\Calendar;
use Statusboard\ControllerHelpers\CalendarHelper;
use Symfony\Component\HttpFoundation\Response;

class CalendarControllerTest extends ApiBase {

    const ENDPOINT_CALENDAR = '/api/calendar';
    const ENDPOINT_EVENT = self::ENDPOINT_CALENDAR . "/event";
    const ENDPOINT_UPCOMING = self::ENDPOINT_CALENDAR . "/upcoming";

    const CONTENT_TYPE_JSON = ['CONTENT_TYPE' => 'application/json'];

    /**
     * @test
     */
    public function getUpcomingInfo() {
        $crawler = $this->loggedInClient->request("GET", self::ENDPOINT_UPCOMING);
        $response = $this->loggedInClient->getResponse();
        $body = json_decode($response->getContent(), true);
        $this->assertCount(3, $body);
        $eventTypes = CalendarHelper::getEventTypes();
        foreach ($body as $event) {
            $this->assertTrue(in_array($event['display_name'], $eventTypes), 'Event Display Name');
            $this->assertArrayHasKey('display_name', $event);
            $this->assertArrayHasKey('date', $event);
            $this->assertArrayHasKey('days', $event);
        }
    }

    /**
     * @test
     */
    public function getAllCalendarEvents() {
        // there are two different types of get all event pulls, this is the one used by the UI
        $crawler = $this->loggedInClient->request("GET", self::ENDPOINT_CALENDAR . "?format=byDate");
        $response = $this->loggedInClient->getResponse();
        $body = json_decode($response->getContent(), true);
        foreach ($body as $date => $events) {
            $this->assertRegExp('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $date, 'Invalid date returned "' . $date . '"');
            $this->assertIsArray($events);
            foreach ($events['events'] as $event) {
                $this->assertArrayHasKey('id', $event);
                $this->assertArrayHasKey('type_id', $event);
                $this->assertArrayHasKey('description', $event);
                $this->assertArrayHasKey('description_raw', $event);
                $this->assertArrayHasKey('date', $event);
            }
        }
        // there are two different types of get all event pulls, this is the one is the per event
        $crawler = $this->loggedInClient->request("GET", self::ENDPOINT_CALENDAR);
        $response = $this->loggedInClient->getResponse();
        $body = json_decode($response->getContent(), true);
        foreach ($body as $id => $event) {
            $this->assertEquals($id, $event['id']);
            $this->assertArrayHasKey('id', $event);
            $this->assertArrayHasKey('type_id', $event);
            $this->assertArrayHasKey('description', $event);
            $this->assertArrayHasKey('description_raw', $event);
            $this->assertArrayHasKey('date', $event);
        }
    }

    /**
     * @test
     */
    public function updateCalendarEvent() {
        $update_data = [
            'type_id'     => Calendar::TYPE_NATIONAL_HOLIDAY,
            'description' => 'Someone\'s Birthday',
            'date'        => '1978-04-07',
        ];
        $crawler = $this->loggedInClient->request("GET", self::ENDPOINT_EVENT . "/1");
        $response = $this->loggedInClient->getResponse();
        $body = json_decode($response->getContent(), true);

        $original_data = [
            'type_id'     => $body['type_id'],
            'description' => $body['description'],
            'date'        => $body['date'],
        ];

        $crawler = $this->loggedInClient->request("PATCH", self::ENDPOINT_EVENT . "/1", [], [], self::CONTENT_TYPE_JSON, json_encode($update_data));
        $crawler = $this->loggedInClient->request("GET", self::ENDPOINT_EVENT . "/1");

        $response = $this->loggedInClient->getResponse();
        $body = json_decode($response->getContent(), true);

        $this->assertEquals($update_data['type_id'], $body['type_id']);
        $this->assertEquals($update_data['description'], $body['description']);
        $this->assertEquals($update_data['date'], $body['date']);

        $original_data['description'] = null;
        $crawler = $this->loggedInClient->request("PATCH", self::ENDPOINT_EVENT . "/1", [], [], self::CONTENT_TYPE_JSON, json_encode($original_data));
        $crawler = $this->loggedInClient->request("GET", self::ENDPOINT_EVENT . "/1");

        $response = $this->loggedInClient->getResponse();
        $body = json_decode($response->getContent(), true);

        $this->assertEquals($original_data['type_id'], $body['type_id']);
        $this->assertEquals($original_data['description'], $body['description_raw']);
        $this->assertEquals($original_data['date'], $body['date']);

    }

    /**
     * @test
     */
    public function createDeleteCalendarEvent() {
        $new_data = [
            'type_id'     => Calendar::TYPE_NATIONAL_HOLIDAY,
            'description' => 'Someone\'s Birthday',
            'date'        => '1978-04-07',
        ];
        $crawler = $this->loggedInClient->request("POST", self::ENDPOINT_EVENT, [], [], self::CONTENT_TYPE_JSON, json_encode($new_data));
        $this->assertEquals(Response::HTTP_FOUND, $this->loggedInClient->getResponse()->getStatusCode(), 'Redirect Response Code Error');
        $crawler = $this->loggedInClient->followRedirect();

        $this->assertEquals(Response::HTTP_OK, $this->loggedInClient->getResponse()->getStatusCode(), 'Response Code Error');
        $response = $this->loggedInClient->getResponse();
        $body = json_decode($response->getContent(), true);

        $id = $body['id'];

        $this->assertEquals($new_data['type_id'], $body['type_id']);
        $this->assertEquals($new_data['date'], $body['date']);
        $this->assertEquals($new_data['description'], $body['description']);

        $crawler = $this->loggedInClient->request("DELETE", self::ENDPOINT_EVENT . "/" . $id);
        $this->assertEquals(Response::HTTP_OK, $this->loggedInClient->getResponse()->getStatusCode(), 'Response Code Error');
        $response = $this->loggedInClient->getResponse();
        $body = $response->getContent();
        $this->assertEquals("\"Calendar Event: ${id} removed\"", $body, "Delete response message");

    }
}
