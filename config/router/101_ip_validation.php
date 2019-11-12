<?php
/**
 * Ip Validation Controller
 */
return [
    "routes" => [
        [
            "info" => "controller for ip validation",
            "mount" => "validate-ip",
            "handler" => "\Niko\IpValidation\IpValidationWebController",
        ]
    ]
];
