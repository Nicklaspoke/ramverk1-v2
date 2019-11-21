<?php

namespace Niko\IpValidation;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

class IpValidationJSONControllerTest extends TestCase
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
        $this->controller = new IpValidationJSONController();
        $this->controller->setDI($this->di);
        $this->controller->initialize();
    }

    /**
     * Test the route "index".
     */
    // public function testIndexAction()
    // {
    //     $res = $this->controller->indexActionGet();

    //     // Check the responce object
    //     $this->assertIsObject($res);
    //     $this->assertInstanceOf("\Anax\Response\Response", $res);
    //     $this->assertInstanceOf("\Anax\Response\ResponseUtility", $res);
    // }

    public function testjsonActionGetValid()
    {
        $_GET["ip"] = "8.8.8.8";
        $_GET["kmom"] = "kmom01";

        $res = $this->controller->jsonActionGet();

        $this->assertInternalType("array", $res);

        $json = $res[0];
        $this->assertEquals("8.8.8.8", $json["ip"]);
        $this->assertEquals(true, $json["valid"]);
        $this->assertEquals("dns.google", $json["hostname"]);
    }

    public function testjsonActionGetInvalid()
    {
        $_GET["ip"] = "300.8.8.8";
        $_GET["kmom"] = "kmom01";

        $res = $this->controller->jsonActionGet();

        $this->assertInternalType("array", $res);

        $json = $res[0];
        $this->assertEquals("300.8.8.8", $json["ip"]);
        $this->assertEquals(false, $json["valid"]);
    }

    public function testjsonActionGetGeoInfo()
    {
        $_GET["ip"] = "8.8.8.8";
        $_GET["kmom"] = "kmom02";

        $res = $this->controller->jsonActionGet();

        $this->assertInternalType("array", $res);

        $json = $res[0];
        $geoData = $json["geoinfo"];
        $this->assertEquals("8.8.8.8", $geoData["ip"]);
        $this->assertEquals("United States", $geoData["country_name"]);
        $this->assertEquals("37.419158935546875", $geoData["latitude"]);
        $this->assertEquals("-122.07540893554688", $geoData["longitude"]);
    }
}
