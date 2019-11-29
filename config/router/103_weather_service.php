<?php
/**
 * Ip Validation Controller
 */
return [
    "routes" => [
        [
            "info" => "controller for Weather forecast service",
            "mount" => "weather-web",
            "handler" => "\Niko\WeatherService\WeatherController",
        ]
    ]
];
