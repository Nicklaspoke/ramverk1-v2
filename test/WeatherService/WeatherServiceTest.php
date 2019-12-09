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
        // $this->di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache");

        // View helpers uses the global $di so it needs its value
        $di = $this->di;

        $this->weatherService = new WeatherService();

        $this->apiInfo = $this->di->get("apiInfo")->getApiKey("darkSky");
    }

    public function validDataProvider()
    {
        return [
            [
                [
                "lat" => "56.161220550537",
                "lon" => "15.586899757385",
                ]
            ],
            [
                [
                "lat" => "37.419158935547",
                "lon" => "-122.07540893555"
                ]
            ],
        ];
    }

    public function invalidDataProvider()
    {
        return [
            [
                [
                "lat" => "560.161220550537",
                "lon" => "15.586899757385",
                ],
                "forecast"
            ],
            [
                [
                "lat" => "37.419158935547",
                "lon" => "-1202.07540893555"
                ],
            ],
        ];
    }

    /**
     * Test to get data when inputs are valid
     *
     * @dataProvider validDataProvider
     */
    public function testGetWeatherDataValidForecast($location)
    {
        $res = $this->weatherService->getWeatherData($location, "forecast", $this->apiInfo);

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
    public function testGetWeatherDataInvalid($location)
    {
        $res = $this->weatherService->getWeatherData($location, "forecast", $this->apiInfo);

        $this->assertArrayHasKey("message", $res);
        $this->assertEquals("Invalid lat or lon coordinates", $res["message"]);
    }

    /**
     * Test that an error is returned when invalid option is sent
     *
     * @dataProvider validDataProvider
     */
    public function testGetWeatherDataInvalidOption($location)
    {
        $res = $this->weatherService->getWeatherData($location, "notAnOption", $this->apiInfo);


        $this->assertArrayHasKey("message", $res);
        $this->assertEquals("Invalid option parameter", $res["message"]);
    }

    /**
     * Test getting previous weather, only send one call, cause the big amount of api calls it makes
     */
    public function testGetPreviousWeatherValid()
    {
        $location = [
            "lat" => "56.161220550537",
            "lon" => "15.586899757385",
        ];

        $res = $this->weatherService->getWeatherData($location, "previous", $this->apiInfo);

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
        $this->assertArrayHasKey("bottomRight", $mapCoords);
        $this->assertArrayHasKey("topLeft", $mapCoords);
        $this->assertArrayHasKey("lat", $geoData);
        $this->assertArrayHasKey("lon", $geoData);
    }

    public function testGetPreviousWeatherInvalid()
    {
        $location = [
            "lat" => "560.161220550537",
            "lon" => "15.586899757385",
        ];

        $res = $this->weatherService->getWeatherData($location, "previous", $this->apiInfo);


        $this->assertArrayHasKey("message", $res);
        $this->assertEquals("Invalid lat or lon coordinates", $res["message"]);
    }
}
