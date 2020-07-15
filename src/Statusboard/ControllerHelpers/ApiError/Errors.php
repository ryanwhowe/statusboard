<?php

namespace Statusboard\ControllerHelpers\ApiError;

/**
 * Class Errors
 *
 * Abstract base error class
 *
 * @package Statusboard\ControllerHelpers\ApiError
 */
abstract class Errors {

    /**
     * Returns the error message for the supplied code
     *
     * @param int   $errorCode
     * @param array $errorParams
     *
     * @return string
     * @throws \Exception
     */
    public static function errorMessage(int $errorCode, array $errorParams = []) {
        if (!isset(static::$errors[$errorCode])) {
            throw new \Exception("Invalid Error Code: " . $errorCode);
        }
        if (count($errorParams)) {
            return vsprintf(static::$errors[$errorCode], $errorParams);
        }
        return static::$errors[$errorCode];
    }

    /**
     * Return the group id
     *
     * @return int|string
     */
    public static function groupId() {
        return static::$groupId;
    }

    /**
     * Return the full response object with error codes attached
     *
     * @param int   $returnCode   the return error code
     * @param array $errorData    Optional additional error data
     * @param array $responseData Optional additional response data
     * @param array $errorParams  Optional array of parameters to be replaced in the error message string
     *
     * @return array
     * @throws \Exception
     */
    public static function response(int $returnCode, array $errorData = [], array $responseData = [], array $errorParams = []) {
        return [
            'errorGroup' => static::groupId(),
            'returnCode' => $returnCode,
            'message'    => self::errorMessage($returnCode, $errorParams),
            'errorData'  => $errorData,
            'data'       => $responseData,
        ];
    }
}