<?php


namespace Statusboard\Mbta;


class Transform {

    CONST SOURCE_TIMEOUT = 'timeout';

    CONST OUTPUT_TIMEOUT = 'timeout';

    public static function responseProcessor(array $source_data): array{
        $result = [];
            $result[self::OUTPUT_TIMEOUT] = $source_data[self::SOURCE_TIMEOUT];
        return $result;
    }
}