<?php

namespace Niko\WeatherService;

use PHPUnit\Framework\TestCase;

class ApinInfoTest extends TestCase
{
    /**
     * Setup fucntion for the tests
     */
    public function setUp()
    {
        $this->apiInfo = new ApiInfo();
    }

    public function testConstructor()
    {
        $this->assertIsObject($this->apiInfo);
        // $this->assertInternalType("array", $this->apiInfo);
    }

    public function validDataProvider()
    {
        return [
            ["darkSkey","https://api.darksky.net/forecast/","abcdef123456"],
            ["21erfe","https://vnslkvsdlvds.com","[]cscd[]"],
            ["123456789","Https://123456789.com","$, socmcoscosms"],
        ];
    }
    /**
     * Tests if assignment and retriving of apiKeys work
     *
     * @dataProvider validDataProvider
     */
    public function testGetAndSetApiKey($serviceName, $baseUrl, $apiKey)
    {
        $this->apiInfo->setApiKey($serviceName, $baseUrl, $apiKey);

        $info = $this->apiInfo->getApiKey($serviceName);

        $this->assertInternalType("array", $info);
        $this->assertEquals($baseUrl, $info["baseUrl"]);
        $this->assertEquals($apiKey, $info["apiKey"]);
    }
}
