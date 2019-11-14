<?php
/**
 * Ip Validation Controller
 */
return [
    "routes" => [
        [
            "info" => "controller for ip validation",
            "mount" => "validate-ip-web",
            "handler" => "\Niko\IpValidation\IpValidationWebController",
        ]
    ]
];
