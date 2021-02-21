<?php

namespace Tests\Statusboard\Mbta;

use Statusboard\Mbta\MockFetcher;
use Statusboard\Mbta\Transform;
use PHPUnit\Framework\TestCase;

class TransformTest extends TestCase {

    public function getScheduleData() {
        $schedule = MockFetcher::getSchedule('', '');
        return Transform::getArrayResponseBody($schedule);
    }

    /**
     * @test
     */
    public function responseProcessor() {
        $response = Transform::responseProcessor($this->getScheduleData());
        $this->assertArrayHasKey('expires', $response);
        $this->assertArrayHasKey('trips', $response);
    }

    /**
     * @test
     */
    public function filterTrips() {
        $filter_time = 1583519246; // this is an appropriate epoch time for when the test data was delivered 2020-03-06 @ 1:27:26 PM
        $stops = Transform::filterStops($this->getScheduleData(), [Transform::STATION_FILTER_SOUTHSTATION, Transform::STATION_FILTER_FORGEPARK]);
        $trips = Transform::parseTripData($stops);
        [$expires, $filtered_trips] = Transform::filterTripsByTime($trips, $filter_time);
        $next_train_time = 1589800800;
        $this->assertEquals($next_train_time, $expires, 'Expiration time should be next train departure time');

        $this->assertEquals(1703, $filtered_trips[0]['trip']);
        $this->assertEquals(1705, $filtered_trips[1]['trip']);
        $this->assertEquals(1707, $filtered_trips[2]['trip']);

        $this->assertEquals($next_train_time, $filtered_trips[0]['departs']);
        $this->assertEquals(1589808000, $filtered_trips[1]['departs']);
        $this->assertEquals(1589815200, $filtered_trips[2]['departs']);

        $this->assertEquals(1589804640, $filtered_trips[0]['arrives']);
        $this->assertEquals(1589811840, $filtered_trips[1]['arrives']);
        $this->assertEquals(1589819040, $filtered_trips[2]['arrives']);
    }

    /**
     * @test
     */
    public function filterStops() {
        $filtered = Transform::filterStops($this->getScheduleData(), [Transform::STATION_FILTER_SOUTHSTATION]);
        $this->assertCount(10, $filtered);
        $this->assertArrayHasKey(0, $filtered);
        $this->assertArrayHasKey(14, $filtered);
        $this->assertArrayHasKey(28, $filtered);
        $this->assertArrayHasKey(42, $filtered);
        $this->assertArrayHasKey(56, $filtered);
        $this->assertArrayHasKey(70, $filtered);
        $this->assertArrayHasKey(84, $filtered);
        $this->assertArrayHasKey(98, $filtered);
        $this->assertArrayHasKey(112, $filtered);
        $this->assertArrayHasKey(126, $filtered);
    }

    /**
     * @test
     */
    public function filterStopsByStopId(){
        $filtered = Transform::filterStops($this->getScheduleData(), [Transform::STATION_FILTER_SOUTHSTATION]);
        $this->assertCount(10, $filtered);
        $this->assertArrayHasKey(0, $filtered);
        $this->assertArrayHasKey(14, $filtered);
        $this->assertArrayHasKey(28, $filtered);
        $this->assertArrayHasKey(42, $filtered);
        $this->assertArrayHasKey(56, $filtered);
        $this->assertArrayHasKey(70, $filtered);
        $this->assertArrayHasKey(84, $filtered);
        $this->assertArrayHasKey(98, $filtered);
        $this->assertArrayHasKey(112, $filtered);
        $this->assertArrayHasKey(126, $filtered);
    }

    /**
     * @test
     */
    public function parseTrips() {
        $stops = Transform::filterStops($this->getScheduleData(), [Transform::STATION_FILTER_SOUTHSTATION, Transform::STATION_FILTER_FORGEPARK]);
        $trips = Transform::parseTripData($stops);
        $this->assertCount(10, $trips);
        foreach ($trips as $trip) {
            $this->assertArrayHasKey('trip', $trip);
            $this->assertArrayHasKey('departs', $trip);
            $this->assertArrayHasKey('arrives', $trip);
        }
    }
}
