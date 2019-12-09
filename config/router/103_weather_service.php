<?php
/**
 * Controller for loading the WeatherService container
 */
return [
    "routes" => [
        [
            "info" => "Controller for weather forecast service",
            "mount" => "weather-web",
            "handler" => "\Niko\WeatherService\WeatherController",
        ]
    ]
];
