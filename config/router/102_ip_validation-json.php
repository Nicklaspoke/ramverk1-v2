<?php
/**
 * Ip Validation Controller
 */
return [
    "routes" => [
        [
            "info" => "controller for ip validation",
            "mount" => "validate-ip-json",
            "handler" => "\Niko\IpValidation\IpValidationJSONController",
        ]
    ]
];
