<?php

namespace Niko\WeatherService;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

class WeatherJSONContollerTest extends TestCase
{
    /**
     * Setup for the testcases
     */

    public function setup()
    {
        global $di;

        // Setup di
        $this->di = new DIFactoryConfig();
        $this->di->loadServices(ANAX_INSTALL_PATH . "/config/di");

        // Use a different cache dir for unit test
        $this->di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache");

        // View helpers uses the global $di so it needs its value
        $di = $this->di;

        // Setup the controller
        $this->controller = new WeatherJSONController();
        $this->controller->setDI($this->di);
        $this->controller->initialize();

        $this->request = $this->di->get("request");
    }

    public function testInitialize()
    {
        $this->assertIsObject($this->controller->weatherService);
        $this->assertIsObject($this->controller->ipValidator);
        $this->assertIsObject($this->controller->geolocator);
        $this->assertInstanceOf("\Niko\WeatherService\WeatherService", $this->controller->weatherService);
        $this->assertInstanceOf("\Niko\WeatherService\IpValidator", $this->controller->ipValidator);
        $this->assertInstanceOf("\Niko\WeatherService\IpGeoLocator", $this->controller->geolocator);
    }

    /**
     * Test indexActionGet
     */
    public function testIndexActionGet()
    {
        $res = $this->controller->indexActionGet();

        // Check the responce object
        $this->assertIsObject($res);
        $this->assertInstanceOf("\Anax\Response\Response", $res);
        $this->assertInstanceOf("\Anax\Response\ResponseUtility", $res);
    }

    /**
     * Test the json reponse when valid data is inserted
     */
    public function testJsonActionGetValidIp()
    {
        $this->request->setGet("input", "8.8.8.8");
        $this->request->setGet("option", "forecast");

        $res = $this->controller->jsonActionGet();

        $this->assertInternalType("array", $res);

        $json = $res[0];

        /**
         * Check if the json responce has the data
         */
        $this->assertArrayHasKey("geoData", $json);
        $this->assertArrayHasKey("weatherData", $json);

        /**
         * Check if the geoData has correct keys and values
         */
        $geoData = $json["geoData"];
        $this->assertArrayHasKey("lat", $geoData);
        $this->assertArrayHasKey("lon", $geoData);
        $this->assertEquals("37.419158935546875", $geoData["lat"]);
        $this->assertEquals("-122.07540893554688", $geoData["lon"]);

        /**
         * Check if weatherData has correct keys
         */
        $weatherData = $json["weatherData"]["weatherData"];
        $this->assertArrayHasKey(date("d/m"), $weatherData);
        $weatherData = $weatherData[date("d/m")];

        $this->assertArrayHasKey("summary", $weatherData);
        $this->assertArrayHasKey("sunrise", $weatherData);
        $this->assertArrayHasKey("sunset", $weatherData);
        $this->assertArrayHasKey("tempetureHigh", $weatherData);
        $this->assertArrayHasKey("tempetureLow", $weatherData);
        $this->assertArrayHasKey("humidity", $weatherData);
        $this->assertArrayHasKey("pressure", $weatherData);
        $this->assertArrayHasKey("windSpeed", $weatherData);
    }

    public function testJsonActionGetValidGeo()
    {
        $this->request->setGet("input", "37.419158935546875,-122.07540893554688");
        $this->request->setGet("option", "forecast");

        $res = $this->controller->jsonActionGet();

        $this->assertInternalType("array", $res);

        $json = $res[0];

        /**
         * Check if the json responce has the data
         */
        $this->assertArrayHasKey("geoData", $json);
        $this->assertArrayHasKey("weatherData", $json);

        /**
         * Check if the geoData has correct keys and values
         */
        $geoData = $json["geoData"];
        $this->assertArrayHasKey("lat", $geoData);
        $this->assertArrayHasKey("lon", $geoData);
        $this->assertEquals("37.419158935546875", $geoData["lat"]);
        $this->assertEquals("-122.07540893554688", $geoData["lon"]);

        /**
         * Check if weatherData has correct keys
         */
        $weatherData = $json["weatherData"]["weatherData"];
        // $this->assertArrayHasKey(date("d/m"), $weatherData);
        $weatherData = $weatherData[date("d/m")];

        $this->assertArrayHasKey("summary", $weatherData);
        $this->assertArrayHasKey("sunrise", $weatherData);
        $this->assertArrayHasKey("sunset", $weatherData);
        $this->assertArrayHasKey("tempetureHigh", $weatherData);
        $this->assertArrayHasKey("tempetureLow", $weatherData);
        $this->assertArrayHasKey("humidity", $weatherData);
        $this->assertArrayHasKey("pressure", $weatherData);
        $this->assertArrayHasKey("windSpeed", $weatherData);
    }


    public function invalidDataProvider()
    {
        return [
            ["127.0.0.1"],
            ["0.0.0.300"],
        ];
    }

    /**
     * Test when the controller recives a invalid ip address
     * or a address that can't be translated to a geolocation ex. 127.0.0.1
     *
     * @dataProvider invalidDataProvider
     */
    public function testJsonActionGetInvalidIp($input)
    {
        $this->request->setGet("input", $input);
        $this->request->setGet("option", "forecast");

        $res = $this->controller->jsonActionGet();

        $this->assertInternalType("array", $res);

        $json = $res[0];
        $this->assertArrayHasKey("message", $json);
        $this->assertContains("Invalid", $json["message"]);
    }

    public function testJsonActionGetInvalidGeo()
    {
        $this->request->setGet("input", "37.419158935546875,-1220.07540893554688");
        $this->request->setGet("option", "forecast");

        $res = $this->controller->jsonActionGet();

        $this->assertInternalType("array", $res);

        $json = $res[0]["weatherData"];
        $this->assertArrayHasKey("message", $json);
        $this->assertContains("Invalid", $json["message"]);
    }
}
