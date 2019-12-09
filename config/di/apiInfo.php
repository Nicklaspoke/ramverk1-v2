<?php
/**
 * Configuration file for loading a service into the DI continer
 */
return [
    "services" => [
        "apiInfo" => [
            "shared" => true,
            "callback" => function () {
                $obj = new \Niko\WeatherService\ApiInfo();
                $keys = require ANAX_INSTALL_PATH . "/config/apiKeys.php";

                foreach ($keys as $service => $data) {
                    $obj->setApiKey($service, $data["baseUrl"], $data["apiKey"]);
                }

                return $obj;
            }
        ],
    ],
];
