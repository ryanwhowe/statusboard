<?php


namespace Statusboard\Mbta;


class TripFilters {

    const TRIP_OUTBOUND = 0;
    const TRIP_INBOUND = 1;

    const HEADSIGN_FORGEPARK = "Forge Park/495";

    public static function tripDirectionFilter($trip_direction){
        if(!self::validateTripDirection($trip_direction)) throw new \InvalidArgumentException('Invalid trip direction \'' . $trip_direction . '\'');
        return function($trip) use ($trip_direction) {
            if ($trip['attributes']['direction_id'] === $trip_direction) {
                return true;
            }
            return false;
        };
    }

    /**
     * @deprecated The route patter has changed and is no longer a reliable filter
     */
    public static function routePatternFilter($route_pattern){
        return function ($trip) use ($route_pattern) {
            if ($trip['relationships']['route_pattern']['data']['id'] === $route_pattern) {
                return true;
            }
            return false;
        };
    }

    public static function headSignFilter($headsign){
        return function ($trip) use ($headsign) {
            if ($trip['attributes']['headsign'] === $headsign) {
                return true;
            }
            return false;
        };
    }

    /**
     * Validate the trip direction
     * @param int $trip_direction
     * @return bool
     */
    private static function validateTripDirection($trip_direction){
        if(in_array($trip_direction, [self::TRIP_INBOUND, self::TRIP_OUTBOUND])) return true;
        return false;
    }
}