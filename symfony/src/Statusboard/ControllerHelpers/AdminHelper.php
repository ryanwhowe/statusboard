<?php


namespace Statusboard\ControllerHelpers;


use Statusboard\Utility\Environment;

class AdminHelper {

    /**
     * @param $type_test
     *
     * @return array
     */
    public static function getEnvironmentTestResults($type_test): array {
        $env = Environment::getType($type_test);
        $envs = [
            'ENV_PRODUCTION'        => ($env === Environment::ENV_PRODUCTION),
            'ENV_DOCKER_PRODUCTION' => ($env === Environment::ENV_DOCKER_PRODUCTION),
            'ENV_DOCKER_DEV'        => ($env === Environment::ENV_DOCKER_DEV),
            'ENV_DEV'               => ($env === Environment::ENV_DEV),
            'ENV_LOCAL'             => ($env === Environment::ENV_LOCAL),
            'ENV_TEST'              => ($env === Environment::ENV_TEST),
            'ENV_DOCKER_TEST'       => ($env === Environment::ENV_DOCKER_TEST),
            'ENV_AWS_PRODUCTION'    => ($env === Environment::ENV_AWS_PRODUCTION),
            'ENV_AWS_STAGING'       => ($env === Environment::ENV_AWS_STAGING),
            'ENV_AWS_DEV'           => ($env === Environment::ENV_AWS_DEV),
            'ENV_AWS_TEST'          => ($env === Environment::ENV_AWS_TEST),
            'isLocal()'             => Environment::isLocal(),
            'isLocalDevelopment()'  => Environment::isLocalDevelopment(),
            'isDevelopment()'       => Environment::isDevelopment(),
            'isTesting()'           => Environment::isTesting(),
            'isAwsTesting()'        => Environment::isAwsTesting(),

        ];
        return $envs;
    }
}