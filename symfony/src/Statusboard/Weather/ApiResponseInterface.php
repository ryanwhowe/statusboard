<?php


namespace Statusboard\Weather;

interface ApiResponseInterface {

    public static function responseProcessor(array $jsonData): array;
}