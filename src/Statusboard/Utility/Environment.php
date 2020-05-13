<?php

namespace Statusboard\Utility;

class Environment
{
    const ENV_PRODUCTION = 1;
    const ENV_DOCKER_PRODUCTION = 2;
    const ENV_DOCKER_DEV = 3;
    const ENV_DEV = 4;
    const ENV_LOCAL = 5;
    const EVN_TEST = 6;
    const ENV_DOCKER_TEST = 7;
    const ENV_AWS_PRODUCTION = 8;
    const ENV_AWS_STAGING = 9;
    const ENV_AWS_DEV = 10;
    const ENV_AWS_TEST = 11;

    public static $type = null;

    public static function getType(){

        if(is_null(self::$type)) {
            self::$type = self::ENV_PRODUCTION;
            $environment = getenv('RUN_ENVIRONMENT');
            if (isset($environment) && strcasecmp($environment, 'docker-dev') == 0) {
                self::$type = self::ENV_DOCKER_DEV;
            } elseif (isset($environment) && strcasecmp($environment, 'dev') == 0) {
                self::$type = self::ENV_DEV;
            } elseif (in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'], true)) {
                self::$type = self::ENV_LOCAL;
            }
        }
        return self::$type;
    }

    public static function isLocal(){
        return (self::getType() === self::ENV_LOCAL);
    }

    public static function isLocalDevelopment(){
        return (self::getType() === self::ENV_DOCKER_DEV);
    }
    public static function isDevelopment(){
        return (self::isLocal() || self::isLocalDevelopment() || (self::getType() === self::ENV_AWS_DEV));
    }
}