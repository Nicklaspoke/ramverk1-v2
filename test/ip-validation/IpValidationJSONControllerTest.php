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
        // Setup di
        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . "/config/di");

         // Use a different cache dir for unit test
        $di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache");

        // Setup the controller
        $this->controller = new IpValidationJSONController();
        $this->controller->setDI($di);
    }

    /**
     * Test the route "index".
     */
    public function testIndexAction()
    {
        $res = $this->controller->indexActionGet();

        // Check the responce object
        $this->assertIsObject($res);
        $this->assertInstanceOf("\Anax\Response\Response", $res);
        $this->assertInstanceOf("\Anax\Response\ResponseUtility", $res);
    }

    public function testjsonActionGetValid()
    {
        $_GET["ip"] = "8.8.8.8";

        $res = $this->controller->jsonActionGet();

        $this->assertInternalType("array", $res);

        $json = $res[0];
        $this->assertContains("8.8.8.8", $json["ip"]);
        $this->assertContains(true, $json["valid"]);
        $this->assertContains("dns.google", $json["hostname"]);
    }

    public function testjsonActionGetInvalid()
    {
        $_GET["ip"] = "300.8.8.8";

        $res = $this->controller->jsonActionGet();

        $this->assertInternalType("array", $res);

        $json = $res[0];
        $this->assertContains("300.8.8.8", $json["ip"]);
        $this->assertContains(false, $json["valid"]);
        $this->assertContains("", $json["hostname"]);
    }
}
