<?php

namespace Statusboard\Utility;

class Environment {
    const ENV_PRODUCTION = 1;
    const ENV_DOCKER_PRODUCTION = 2;
    const ENV_DOCKER_DEV = 3;
    const ENV_DEV = 4;
    const ENV_LOCAL = 5;
    const ENV_TEST = 6;
    const ENV_DOCKER_TEST = 7;
    const ENV_AWS_PRODUCTION = 8;
    const ENV_AWS_STAGING = 9;
    const ENV_AWS_DEV = 10;
    const ENV_AWS_TEST = 11;

    const TYPE_PRODUCTION = 'prod';
    const TYPE_DEV = 'dev';
    const TYPE_DOCKER_DEV = 'docker-dev';
    const TYPE_TEST = 'test';

    public static $type = null;

    /**
     * @param string|null $type
     *
     * @return int
     */
    public static function getType($type = null) {
        if ($type !== null) {
            putenv("RUN_ENVIRONMENT=${type}");
            self::$type = null;
        }
        if (is_null(self::$type)) {
            self::$type = self::ENV_PRODUCTION;
            $environment = getenv('RUN_ENVIRONMENT');
            if (isset($environment) && strcasecmp($environment, self::TYPE_DOCKER_DEV) == 0) {
                self::$type = self::ENV_DOCKER_DEV;
            } elseif (isset($environment) && strcasecmp($environment, self::TYPE_DEV) == 0) {
                self::$type = self::ENV_DEV;
            } elseif (isset($environment) && strcasecmp($environment, self::TYPE_TEST) == 0) {
                self::$type = self::ENV_TEST;
            } elseif (isset($environment) && strcasecmp($environment, self::TYPE_PRODUCTION) == 0) {
                self::$type = self::ENV_PRODUCTION;
            } elseif (in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'], true)) {
                self::$type = self::ENV_LOCAL;
            }
        }
        return self::$type;
    }

    public static function isAwsTesting() {
        return (self::getType() === self::ENV_AWS_TEST);
    }

    public static function isTesting() {
        return (self::getType() === self::ENV_TEST || self::isAwsTesting());
    }

    public static function isLocal() {
        return (self::getType() === self::ENV_LOCAL);
    }

    public static function isLocalDevelopment() {
        return (self::getType() === self::ENV_DOCKER_DEV);
    }

    public static function isDevelopment() {
        return (
            self::isLocal() ||
            self::isLocalDevelopment() ||
            (self::getType() === self::ENV_AWS_DEV) ||
            (self::getType() === self::ENV_DEV)
        );
    }

    /**
     * reset the static variable of the current environment
     */
    public static function reset() {
        self::$type = null;
    }
}