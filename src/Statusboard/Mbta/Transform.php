<?php

namespace Statusboard\Mbta;

class Transform {

    const ID_SCHEDULE_TEXT = 'schedule';
    const ID_CR_TEXT = 'cr';
    const ID_WEEKDAY_WEEKEND = 'weekend_weekday';
    const ID_SCHEDULE_SEASON = 'season';
    const ID_SCHEDULE_YEAR = 'year';
    const ID_TRIP = 'trip';
    const ID_STATION = 'station';
    const ID_ROUTE_STOP_NUMBER = 'stop_number';

    const PARSER_SCHEDULE_TEXT = 0;
    const PARSER_CR_TEXT = 1;
    const PARSER_WEEKDAY_WEEKEND = 2;
    const PARSER_SCHEDULE_SEASON = 3;
    const PARSER_SCHEDULE_YEAR = 4;
    const PARSER_TRIP = 5;
    const PARSER_STATION = 6;
    const PARSER_ROUTE_STOP_NUMBER = 7;

    const STATION_FILTER_SOUTHSTATION = 'South Station';
    const STATION_FILTER_FORGEPARK = 'Forge Park / 495';

    const TRIPS_RETURNED = 6;
    const EXPIRATION_BUFFER = Cache::TIMEOUT_BUFFER + 60; //seconds

    /**
     * IMPORTANT, the Cache::TIMEOUT_BUFFER is included in the expires time returned, along with an extra 60 seconds
     *
     * @param array $source_data
     * @return array
     */
    public static function responseProcessor(array $source_data): array{
        if(empty($source_data)){
            throw new \InvalidArgumentException('No Schedule Data provided');
        }
        $stops = self::filterStops($source_data, [self::STATION_FILTER_FORGEPARK, self::STATION_FILTER_SOUTHSTATION]);
        $trips = self::parseTrips($stops);

        list($lowest_trip_time, $results) = self::filterTrips($trips, time());

        $output = [
            'expires' => $lowest_trip_time + self::EXPIRATION_BUFFER,
            'trips' => $results
        ];
        return $output;
    }

    /**
     * Filter the stops to only be Forge Park and South Station
     *
     * @param array $schedule
     * @param array $stops
     * @return array
     */
    public static function filterStops($schedule, array $stops): array {
        return array_filter($schedule['data'], function ($value) use ($stops) {
            foreach ($stops as $stop) {
                if(stristr($value['id'], $stop)) return true;
            }
            return false;
        });
    }

    /**
     * @param array $result
     * @return array
     */
    public static function parseTrips(array $result): array {
        $results = [];
        foreach ($result AS $data) {
            $station_name = ($data['attributes']['arrival_time'] === null) ? 'departs' : 'arrives';
            $time = ($data['attributes']['arrival_time'] === null) ? $data['attributes']['departure_time'] : $data['attributes']['arrival_time'];
            $time = strtotime($time);
            $station = Transform::idParser($data['id']);
            if (!isset($results[$station[Transform::ID_TRIP]])) {
                $results[$station[Transform::ID_TRIP]] = ['trip' => (int)$station[Transform::ID_TRIP]];
            }
            $results[$station[Transform::ID_TRIP]][$station_name] = $time;
        }
        return array_values($results);
    }

    /**
     * @param array $trips
     * @param int $filter_time
     * @return array
     */
    public static function filterTrips(array $trips, $filter_time = 0): array {
        $lowest_trip_time = PHP_INT_MAX;
        $counter = 0;
        $trips = array_filter($trips, function ($trip) use (&$lowest_trip_time, &$counter, $filter_time) {
            if ($filter_time > $trip['departs']) return false;
            if ($lowest_trip_time > $trip['departs']) $lowest_trip_time = $trip['departs'];
            if ($counter++ >= self::TRIPS_RETURNED) return false;
            return true;
        });
        return array($lowest_trip_time, array_values($trips));
    }

    /**
     * @param array $schedule
     * @param int $filter_time
     * @return int
     */
    public static function getExpirationTime(array $schedule, $filter_time = 0): int {
        if(empty($schedule)){
            throw new \InvalidArgumentException('No Schedule Data provided');
        }
        $stops = self::filterStops($schedule, [self::STATION_FILTER_FORGEPARK, self::STATION_FILTER_SOUTHSTATION]);
        $trips = self::parseTrips($stops);
        list($expiration, $filtered_trips) = self::filterTrips($trips, $filter_time);
        return $expiration;
    }

    /**
     * Parse the id value and break it up into its components
     *
     * @param string $id
     * @return array
     */
    public static function idParser(string $id) {
        $parts = explode('-', $id);
        return [
            self::ID_SCHEDULE_TEXT     => $parts[self::PARSER_SCHEDULE_TEXT],
            self::ID_CR_TEXT           => $parts[self::PARSER_CR_TEXT],
            self::ID_WEEKDAY_WEEKEND   => $parts[self::PARSER_WEEKDAY_WEEKEND],
            self::ID_SCHEDULE_SEASON   => $parts[self::PARSER_SCHEDULE_SEASON],
            self::ID_SCHEDULE_YEAR     => $parts[self::PARSER_SCHEDULE_YEAR],
            self::ID_TRIP              => $parts[self::PARSER_TRIP],
            self::ID_STATION           => $parts[self::PARSER_STATION],
            self::ID_ROUTE_STOP_NUMBER => $parts[self::PARSER_ROUTE_STOP_NUMBER],
        ];
    }
}