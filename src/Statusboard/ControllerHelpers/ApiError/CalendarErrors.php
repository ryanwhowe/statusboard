<?php


namespace Statusboard\ControllerHelpers\ApiError;

/**
 * Class CalendarErrors
 *
 * Error codes specific to the Calendar Api Controller
 *
 * @package Statusboard\ControllerHelpers\ApiError
 */
class CalendarErrors extends Errors {

    /**
     * Define the error group
     *
     * @var string
     */
    protected static $groupId = 'calendar';

    /**
     * Error code constants
     */
    const CALENDAR_EVENT_ID_NOT_FOUND = 200;
    const CALENDAR_MISSING_REQUIRED_PARAMETER = 201;
    const CALENDAR_DUPLICATE_TYPE_DATE = 202;

    /**
     * Define the error messages
     *
     * @var string[]
     */
    protected static $errors = [
        self::CALENDAR_EVENT_ID_NOT_FOUND         => "The supplied calendar event id:'%s' was not found",
        self::CALENDAR_DUPLICATE_TYPE_DATE        => "Event Type for the given date already exists",
        self::CALENDAR_MISSING_REQUIRED_PARAMETER => "Required parameters are missing",
    ];
}