<?php

namespace Niko\IpValidation;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

class IpValidationWebControllerTest extends TestCase
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
        $this->controller = new IpValidationWebController();
        $this->controller->setDI($di);
    }

    public function testDebugAction()
    {
        $res = $this->controller->debugAction();

        $this->assertContains("Debug Of IpValidationWebController", $res);
    }

    public function testIndexActionGet()
    {
        $res = $this->controller->indexActionGet();

        // Check the responce object
        $this->assertIsObject($res);
        $this->assertInstanceOf("\Anax\Response\Response", $res);
        $this->assertInstanceOf("\Anax\Response\ResponseUtility", $res);

        // $this->assertContains("Ipv4 or Ipv6 address to validate:", $body);
    }

    /**
     * Test with an valid ip address
     */
    public function testIndexActionPostValid()
    {
        $_POST["ipInput"] = "8.8.8.8";
        $res = $this->controller->IndexActionPost();

        // Check the responce object
        $this->assertIsObject($res);
        $this->assertInstanceOf("\Anax\Response\Response", $res);
        $this->assertInstanceOf("\Anax\Response\ResponseUtility", $res);

    }

    public function testIndexActionPostInvalid()
    {
        $_POST["ipInput"] = "300.8.8.8";
        $res = $this->controller->IndexActionPost();

        // Check the responce object
        $this->assertIsObject($res);
        $this->assertInstanceOf("\Anax\Response\Response", $res);
        $this->assertInstanceOf("\Anax\Response\ResponseUtility", $res);

    }
}
