<?php

namespace Niko\WeatherService;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

class IpValidationWebControllerTest extends TestCase
{
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
        $this->controller = new WeatherController();
        $this->controller->setDI($this->di);
        $this->controller->initialize();

        $this->request = $di->get("request");
    }

    public function testIndexActionGet()
    {
        $res = $this->controller->indexActionGet();

        // Check the responce object
        $this->assertIsObject($res);
        $this->assertInstanceOf("\Anax\Response\Response", $res);
        $this->assertInstanceOf("\Anax\Response\ResponseUtility", $res);
    }
}
