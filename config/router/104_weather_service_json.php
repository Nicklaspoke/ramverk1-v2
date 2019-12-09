<?php
/**
 * Controller for loading the json api part of the weatherservice
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
