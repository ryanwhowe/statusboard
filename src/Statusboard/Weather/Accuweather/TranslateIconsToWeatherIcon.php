<?php


namespace Statusboard\Weather\Accuweather;


class TranslateIconsToWeatherIcon {

    public static function map(int $accuweather_icon_number){
        switch ($accuweather_icon_number){
            case 1: // Sunny (day)
                return 'wi-day-sunny';
            case 2: // Mostly Sunny (day)
            case 3: // Partly Sunny (day)
            case 4: // Intermittent Clouds (day)
            case 5: // Hazy Sunshine (day)
            case 6: // Mostly Cloudy (day)
                return 'wi-day-cloudy';
            case 7: // Cloudy (day, night)
            case 8: // Dreary (Overcast) (day, night)
                return 'wi-cloudy';
            case 11: // Fog (day, night)
                return 'wi-fog';
            case 12: // Showers (day, night)
                return 'wi-showers';
            case 13: // Mostly Cloudy w/ Showers (day)
            case 14: // Partly Sunny w/ Showers (day)
                return 'wi-day-showers';
            case 15: // T-Storms (day, night)
                return 'wi-thunderstorm';
            case 16: // Mostly Cloudy w/ T-Storms (day)
            case 17: // Partly Sunny w/ T-Storms (day)
                return 'wi-day-thunderstorms';
            case 18: // Rain (day, night)
                return 'wi-rain';
            case 19: // Flurries (day, night)
                return 'wi-snow';
            case 20: // Mostly Cloudy w/ Flurries (day)
            case 21: // Partly Sunny w/ Flurries (day)
                return 'wi-day-snow';
            case 22: // Snow (day, night)
                return 'wi-snow';
            case 23: // Mostly Cloudy w/ Snow (day)
                return 'wi-day-snow';
            case 24: // Ice (day, night)
                return 'wi-snowflake-cold';
            case 25: // Sleet (day, night)
            case 26: // Freezing Rain (day, night)
                return 'wi-sleet';
            case 29: // Rain and Snow (day, night)
                return 'wi-rain-mix';
            case 30: // Hot (day, night)
                return 'wi-hot';
            case 31: // Cold (day, night)
                return 'wi-snowflake-cold';
            case 32: // Windy (day, night)
                return 'wi-windy';
            case 33: // Clear (night)
                return 'wi-night-clear';
            case 34: // Mostly Clear (night)
            case 35: // Partly Cloudy (night)
            case 36: // Intermittent Clouds (night)
            case 37: // Hazy Moonlight (night)
            case 38: // Mostly Cloudy (night)
                return 'wi-night-alt-cloudy';
            case 39: // Partly Cloudy w/ Showers (night)
            case 40: // Mostly Cloudy w/ Showers (night)
                return 'wi-night-alt-showers';
            case 41: // Partly Cloudy w/ T-Storms (night)
            case 42: // Mostly Cloudy w/ T-Storms (night)
                return 'wi-night-alt-thunderstorm';
            case 43: // Mostly Cloudy w/ Flurries (night)
            case 44: // Mostly Cloudy w/ Snow (night)
                return 'wi-night-snow';
                break;
            default:
                return 'wi-na';
        }
    }
}