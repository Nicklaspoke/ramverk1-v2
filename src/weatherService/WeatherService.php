<?php

namespace Niko\WeatherService;

class WeatherService
{
    public function getWeatherData($input, $option)
    {
        if (filter_var($input, FILTER_VALIDATE_IP) != false) {
            //Get geo info

        }
    }
}
