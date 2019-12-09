<?php

namespace Niko\WeatherService;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

class GeoLocatorTest extends TestCase
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

        $this->geoLocator = new IpGeoLocator();

        $this->apiInfo = $this->di->get("apiInfo")->getApiKey("ipstack");
    }

    /**
     * Test getting a location with a valid ip.
     */
    public function testgetGeoLocationValid()
    {
        $res = $this->geoLocator->getGeoLocation("8.8.8.8", $this->apiInfo);

        $this->assertEquals("37.419158935546875", $res["lat"]);
        $this->assertEquals("-122.07540893554688", $res["lon"]);
    }

    public function invalidDataProvider()
    {
        return [
            ["127.0.0.1"],
            ["0.0.0.300"],
        ];
    }

    /**
     * Test getting location with invalid ip and a valid ip but can't be
     * translated into a physical location
     *
     * @dataProvider invalidDataProvider
     */
    public function testgetGeoLocationInvalid($ip)
    {
        $res = $this->geoLocator->getGeoLocation($ip, $this->apiInfo);

        $this->assertEquals(false, $res);
    }
}
