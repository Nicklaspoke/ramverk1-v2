<?php
/**
 * Ip Validation Controller
 */
return [
    "routes" => [
        [
            "info" => "controller for Weather forecast service json api",
            "mount" => "weather-json",
            "handler" => "\Niko\WeatherService\WeatherJSONController",
        ]
    ]
];
