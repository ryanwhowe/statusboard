<?php

namespace Tests\Statusboard\Weather\Accuweather;

use PHPUnit\Framework\TestCase;
use Statusboard\Weather\Accuweather\Transform;

class TransformTest extends TestCase {

    private function getTestBody(): array {
        return [
            "Headline" => [
                "EffectiveDate" => "2020-01-10T07:00:00-05:00",
                "EffectiveEpochDate" => 1578657600,
                "Severity" => 4,
                "Text" => "Becoming noticeably warmer tomorrow and Saturday",
                "Category" => "warmer",
                "EndDate" => "2020-01-11T19:00:00-05:00",
                "EndEpochDate" => 1578787200,
                "MobileLink" => "http://m.accuweather.com/en/us/milford-ma/01757/extended-weather-forecast/593_pc?lang=en-us",
                "Link" => "http://www.accuweather.com/en/us/milford-ma/01757/daily-weather-forecast/593_pc?lang=en-us",
            ],
            "DailyForecasts" => [
                0 => [
                    "Date" => "2020-01-09T07:00:00-05:00",
                    "EpochDate" => 1578571200,
                    "Temperature" => [
                        "Minimum" => [
                            "Value" => 20.0,
                            "Unit" => "F",
                            "UnitType" => 18,
                        ],
                        "Maximum" => [
                            "Value" => 30.0,
                            "Unit" => "F",
                            "UnitType" => 18,
                        ],
                    ],
                    "Day" => [
                        "Icon" => 1,
                        "IconPhrase" => "Sunny",
                        "HasPrecipitation" => false,
                    ],
                    "Night" => [
                        "Icon" => 38,
                        "IconPhrase" => "Mostly cloudy",
                        "HasPrecipitation" => false,
                    ],
                    "Sources" => [
                        0 => "AccuWeather",
                    ],
                    "MobileLink" => "http://m.accuweather.com/en/us/milford-ma/01757/daily-weather-forecast/593_pc?day=1&lang=en-us",
                    "Link" => "http://www.accuweather.com/en/us/milford-ma/01757/daily-weather-forecast/593_pc?day=1&lang=en-us",
                ],
                1 => [
                    "Date" => "2020-01-10T07:00:00-05:00",
                    "EpochDate" => 1578657600,
                    "Temperature" => [
                        "Minimum" => [
                            "Value" => 45.0,
                            "Unit" => "F",
                            "UnitType" => 18,
                        ],
                        "Maximum" => [
                            "Value" => 47.0,
                            "Unit" => "F",
                            "UnitType" => 18,
                        ],
                    ],
                    "Day" => [
                        "Icon" => 6,
                        "IconPhrase" => "Mostly cloudy",
                        "HasPrecipitation" => false,
                    ],
                    "Night" => [
                        "Icon" => 38,
                        "IconPhrase" => "Mostly cloudy",
                        "HasPrecipitation" => false,
                    ],
                    "Sources" => [
                        0 => "AccuWeather",
                    ],
                    "MobileLink" => "http://m.accuweather.com/en/us/milford-ma/01757/daily-weather-forecast/593_pc?day=2&lang=en-us",
                    "Link" => "http://www.accuweather.com/en/us/milford-ma/01757/daily-weather-forecast/593_pc?day=2&lang=en-us"
                ],
                2 => [
                    "Date" => "2020-01-11T07:00:00-05:00",
                    "EpochDate" => 1578744000,
                    "Temperature" => [
                        "Minimum" => [
                            "Value" => 55.0,
                            "Unit" => "F",
                            "UnitType" => 18,
                        ],
                        "Maximum" => [
                            "Value" => 59.0,
                            "Unit" => "F",
                            "UnitType" => 18,
                        ],
                    ],
                    "Day" => [
                        "Icon" => 6,
                        "IconPhrase" => "Mostly cloudy",
                        "HasPrecipitation" => false,
                    ],
                    "Night" => [
                        "Icon" => 7,
                        "IconPhrase" => "Cloudy",
                        "HasPrecipitation" => true,
                        "PrecipitationType" => "Rain",
                        "PrecipitationIntensity" => "Light",
                    ],
                    "Sources" => [
                        0 => "AccuWeather",
                    ],
                    "MobileLink" => "http://m.accuweather.com/en/us/milford-ma/01757/daily-weather-forecast/593_pc?day=3&lang=en-us",
                    "Link" => "http://www.accuweather.com/en/us/milford-ma/01757/daily-weather-forecast/593_pc?day=3&lang=en-us",
                ],
                3 => [
                    "Date" => "2020-01-12T07:00:00-05:00",
                    "EpochDate" => 1578830400,
                    "Temperature" => [
                        "Minimum" => [
                            "Value" => 32.0,
                            "Unit" => "F",
                            "UnitType" => 18,
                        ],
                        "Maximum" => [
                            "Value" => 62.0,
                            "Unit" => "F",
                            "UnitType" => 18,
                        ],
                    ],
                    "Day" => [
                        "Icon" => 12,
                        "IconPhrase" => "Showers",
                        "HasPrecipitation" => true,
                        "PrecipitationType" => "Rain",
                        "PrecipitationIntensity" => "Moderate",
                    ],
                    "Night" => [
                        "Icon" => 35,
                        "IconPhrase" => "Partly cloudy",
                        "HasPrecipitation" => false,
                    ],
                    "Sources" => [
                        0 => "AccuWeather",
                    ],
                    "MobileLink" => "http://m.accuweather.com/en/us/milford-ma/01757/daily-weather-forecast/593_pc?day=4&lang=en-us",
                    "Link" => "http://www.accuweather.com/en/us/milford-ma/01757/daily-weather-forecast/593_pc?day=4&lang=en-us",
                ],
                4 => [
                    "Date" => "2020-01-13T07:00:00-05:00",
                    "EpochDate" => 1578916800,
                    "Temperature" => [
                        "Minimum" => [
                            "Value" => 29.0,
                            "Unit" => "F",
                            "UnitType" => 18,
                        ],
                        "Maximum" => [
                            "Value" => 44.0,
                            "Unit" => "F",
                            "UnitType" => 18,
                        ],
                    ],
                    "Day" => [
                        "Icon" => 4,
                        "IconPhrase" => "Intermittent clouds",
                        "HasPrecipitation" => false,
                    ],
                    "Night" => [
                        "Icon" => 34,
                        "IconPhrase" => "Mostly clear",
                        "HasPrecipitation" => false,
                    ],
                    "Sources" => [
                        0 => "AccuWeather",
                    ],
                    "MobileLink" => "http://m.accuweather.com/en/us/milford-ma/01757/daily-weather-forecast/593_pc?day=5&lang=en-us",
                    "Link" => "http://www.accuweather.com/en/us/milford-ma/01757/daily-weather-forecast/593_pc?day=5&lang=en-us",
                ]
            ],
            "timeout" => 1578590626,
            "current" => [
                0 => [
                    "LocalObservationDateTime" => "2020-01-09T11:57:00-05:00",
                    "EpochTime" => 1578589020,
                    "WeatherText" => "Sunny",
                    "WeatherIcon" => 1,
                    "HasPrecipitation" => false,
                    "PrecipitationType" => null,
                    "IsDayTime" => true,
                    "Temperature" => [
                        "Metric" => [
                            "Value" => -3.2,
                            "Unit" => "C",
                            "UnitType" => 17,
                        ],
                        "Imperial" => [
                            "Value"    => 26.0,
                            "Unit"     => "F",
                            "UnitType" => 18,
                        ],
                    ],
                    "MobileLink" => "http://m.accuweather.com/en/us/milford-ma/01757/current-weather/593_pc?lang=en-us",
                    "Link" => "http://www.accuweather.com/en/us/milford-ma/01757/current-weather/593_pc?lang=en-us",
                ],
                "timeout" => 1578590505,
            ],
            "request_limit" => 35,
        ];
    }

    private function getResponseBody(): array {
        return [
            0 => [
                "date" => 1578571200,
                "day" => "Thursday",
                "hightemp" => 30,
                "lowtemp" => 20,
                "icons" => [
                    "day" => "assets/images/weather/accuweather/01-s.png",
                    "night" => "assets/images/weather/accuweather/38-s.png",
                ],
                "icontext" => [
                    "day" => "Sunny",
                    "night" => "Mostly cloudy",
                ],
                "weather-icons" => [
                    "day" => "wi-day-sunny",
                    "night" => "wi-night-alt-cloudy",
                ],
            ],
            1 => [
                "date" => 1578657600,
                "day" => "Friday",
                "hightemp" => 47,
                "lowtemp" => 45,
                "icons" => [
                    "day" => "assets/images/weather/accuweather/06-s.png",
                    "night" => "assets/images/weather/accuweather/38-s.png",
                ],
                "icontext" => [
                    "day" => "Mostly cloudy",
                    "night" => "Mostly cloudy",
                ],
                "weather-icons" => [
                    "day" => "wi-day-cloudy",
                    "night" => "wi-night-alt-cloudy",
                ],
            ],
            2 => [
                "date" => 1578744000,
                "day" => "Saturday",
                "hightemp" => 59,
                "lowtemp" => 55,
                "icons" => [
                    "day" => "assets/images/weather/accuweather/06-s.png",
                    "night" => "assets/images/weather/accuweather/07-s.png",
                ],
                "icontext" => [
                    "day" => "Mostly cloudy",
                    "night" => "Light Rain Cloudy",
                ],
                "weather-icons" => [
                    "day" => "wi-day-cloudy",
                    "night" => "wi-cloudy",
                ],
            ],
            3 => [
                "date" => 1578830400,
                "day" => "Sunday",
                "hightemp" => 62,
                "lowtemp" => 32,
                "icons" => [
                    "day" => "assets/images/weather/accuweather/12-s.png",
                    "night" => "assets/images/weather/accuweather/35-s.png",
                ],
                "icontext" => [
                    "day" => "Moderate Rain Showers",
                    "night" => "Partly cloudy",
                ],
                "weather-icons" => [
                    "day" => "wi-showers",
                    "night" => "wi-night-alt-cloudy",
                ],
            ],
            4 => [
                "date" => 1578916800,
                "day" => "Monday",
                "hightemp" => 44,
                "lowtemp" => 29,
                "icons" => [
                    "day" => "assets/images/weather/accuweather/04-s.png",
                    "night" => "assets/images/weather/accuweather/34-s.png",
                ],
                "icontext" => [
                    "day"   => "Intermittent clouds",
                    "night" => "Mostly clear",
                ],
                "weather-icons" => [
                    "day"   => "wi-day-cloudy",
                    "night" => "wi-night-alt-cloudy",
                ],
            ],
            "headline" => "Becoming noticeably warmer tomorrow and Saturday",
            "expires" => 1578590626,
            "request_limit" => 35,
            "current" => [
                "condition"    => "Sunny",
                "temp"         => 26,
                "link"         => "http://www.accuweather.com/en/us/milford-ma/01757/current-weather/593_pc?lang=en-us",
                "icon"         => "assets/images/weather/accuweather/01-s.png",
                "weather-icon" => "wi-day-sunny",
            ],
        ];
    }

    /**
     * @test
     */
    public function responseProcessor() {
        $body = $this->getTestBody();
        $response_layout = $this->getResponseBody();
        $result = Transform::responseProcessor($body);
        $this->assertEquals($response_layout, $result);
    }
}
