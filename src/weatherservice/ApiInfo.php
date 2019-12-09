<?php

namespace Niko\WeatherService;

/**
 * Class that holds info for different Api calls in the site.
 * Will be loaded through the di config
 */
class ApiInfo
{
    /**
     * @var array $apiKeys  Assosiative array that holds the api servicename and their baseUrl and ApiKey
     */
    private $apiKeys;

    public function __construct()
    {
        $this->apiKeys = [];
    }

    /**
     * Fucntion that handles the assignment of apiKeys to the object.
     *
     * @var string $serviceName The name of the api or what you want to call the api service
     * @var string $baseUrl     The base url for the api to use when calling it
     * @var string $apiKey      The apiKey for the given api service
     *
     * @return void
     */
    public function setApiKey($serviceName, $baseUrl, $apiKey)
    {
        $this->apiKeys[$serviceName] = [
            "baseUrl" => $baseUrl,
            "apiKey" => $apiKey,
        ];
    }

    /**
     * Gets the api info for the given api service
     *
     * @var string $serviceName The name of the api service you want info about
     *
     * @return array with base url and api key
     */
    public function getApiKey($serviceName)
    {
        return $this->apiKeys[$serviceName];
    }
}
