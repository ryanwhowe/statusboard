<?php

namespace Statusboard\ControllerHelpers\ApiError;

/**
 * Class ServerErrors
 *
 * Error codes specific to the Server Api Controller
 *
 * @package Statusboard\ControllerHelpers\ApiError
 */
class ServerErrors extends Errors {

    /**
     * Degine the error group
     *
     * @var string
     */
    protected static $groupId = 'server';

    /**
     * Error code constants
     */
    const SERVER_ID_NOT_FOUND = 100;
    const SERVER_DUPLICATE_NAME = 101;
    const SERVER_MISSING_REQUIRED_PARAMETERS = 102;
    const SERVER_ID_INVALID = 103;

    /**
     * Define the error messages
     *
     * @var string[]
     */
    protected static $errors = [
        self::SERVER_ID_NOT_FOUND                => "The supplied server id:'%s' was not found",
        self::SERVER_DUPLICATE_NAME              => "The provided name:'%s' is already present in the database",
        self::SERVER_MISSING_REQUIRED_PARAMETERS => "Required parameters are missing",
        self::SERVER_ID_INVALID                  => "An integer value must be sent for a server id value",
    ];
}