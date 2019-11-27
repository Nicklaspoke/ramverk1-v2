<?php
/**
 * Configuration file for DI container.
 */
return [

    // Services to add to the container.
    "services" => [
        "apiInfo" => [
            "shared" => true,
            "callback" => function () {
                $obj = new \Niko\ApiInfo\ApiInfo();
                $keys = require ANAX_INSTALL_PATH . "/config/apiKeys.php";

                foreach ($keys as $service => $data) {
                    $obj->setApiKey($service, $data["baseUrl"], $data["apiKey"]);
                }
                return $obj;
            }
        ],
    ],
];
