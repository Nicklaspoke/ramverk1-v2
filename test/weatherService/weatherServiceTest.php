<?php

namespace Niko\WeatherService;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

class WeatherServiceTest extends TestCase
{
    public function setUp()
    {
        global $di;

        // Setup di
        $this->di = new DIFactoryConfig();
        $this->di->loadServices(ANAX_INSTALL_PATH . "/config/di");

        // Use a different cache dir for unit test
        $this->di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache");

        // View helpers uses the global $di so it needs its value
        $di = $this->di;

        $this->weatherService = new WeatherService();

        $this->apiInfo = $this->di->get("apiInfo");
    }

    public function testConstructor()
    {
        $this->assertInstanceOf("\Niko\IpValidation\IpValidator", $this->weatherService->ipValidator);
    }

    /**
     * Only testing for 1 api calla against the forecast part
     * to prevent exciding the API call limit when testing
     */
    public function validDataProvider()
    {
        return [
            ["8.8.8.8"],
            ["194.47.129.126"],
            ["56.161220550537,15.586899757385"],
            ["37.419158935547,-122.07540893555"]
        ];
    }

    public function invalidDataProvider()
    {
        return [
            ["127.0.0.1"],
            ["0.0.0.0"],
            ["506.161220550537,15.586899757385"],
            ["37.419158935547,-1220.07540893555"]
        ];
    }

    /**
     * Test to get data when inputs are valid
     *
     * @dataProvider validDataProvider
     */
    public function testGetWeatherDataValidForecast($data)
    {
        $res = $this->weatherService->getWeatherData($data, "forecast", $this->apiInfo);

        /**
         * Check if the responce is an array and has the right keys
         */
        $this->assertIsArray($res);
        $this->assertArrayHasKey("weatherData", $res);
        $this->assertArrayHasKey("mapCoords", $res);
        $this->assertArrayHasKey("geoData", $res);

        /**
         * Get each individual array
         */
        $weatherData = $res["weatherData"];
        $mapCoords = $res["mapCoords"];
        $geoData = $res["geoData"];

        /**
         * Check if every internal array is an correct array
         */
        $this->assertIsArray($weatherData);
        $this->assertIsArray($mapCoords);
        $this->assertIsArray($geoData);

        /**
         * Check if each of those arrays has the correct keys
         */
        $this->assertArrayHasKey(date("d/m"), $weatherData);
        $this->assertArrayHasKey("bottomRight", $mapCoords);
        $this->assertArrayHasKey("topLeft", $mapCoords);
        $this->assertArrayHasKey("lat", $geoData);
        $this->assertArrayHasKey("lon", $geoData);
    }

    /**
     * Test to get data when inputs are invalid
     *
     * @dataProvider invalidDataProvider
     */
    public function testGetWeatherDataInvalid($data)
    {
        $res = $this->weatherService->getWeatherData($data, "forecast", $this->apiInfo);

        $this->assertEquals(false, $res);
    }
}
