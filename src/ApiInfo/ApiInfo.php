<?php

namespace Niko\ApiInfo;

/**
 * Class that holds info for different Api calls in the site.
 * Will be loaded through the di config
 */
class ApiInfo
{
    /**
     * @var array $apiKeys  Assosiative array that holds servicenames and their apiKey
     */
    private $apiKeys;

    public function __construct()
    {
        $this->apiKeys = [];
    }

    /**
     *
     */
    public function setApiKey($serviceName, $baseUrl, $apiKey)
    {
        $this->apiKeys[$serviceName] = [
            "baseUrl" => $baseUrl,
            "apiKey" => $apiKey,
        ];
    }

    public function getApiKey($serviceName)
    {
        return $this->apiKeys[$serviceName];
    }
}
