<?php

namespace Niko\WeatherService;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

class WeatherControllerTest extends TestCase
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

        // Setup the controller
        $this->controller = new WeatherController();
        $this->controller->setDI($this->di);
        $this->controller->initialize();

        $this->request = $this->di->get("request");
    }

    public function testIndexActionGet()
    {
        $res = $this->controller->indexActionGet();

        // Check the responce object
        $this->assertIsObject($res);
        $this->assertInstanceOf("\Anax\Response\Response", $res);
        $this->assertInstanceOf("\Anax\Response\ResponseUtility", $res);
    }

    public function dataProvider()
    {
        return [
            ["8.8.8.8"],
            ["127.0.0.1"],
            ["127.300.0.1"],
            ["37.419158935546875,-1220.07540893554688"],
            ["37.419158935546875,-122.07540893554688"]
        ];
    }

    /**
     * @dataProvider dataProvider
     */
    public function testIndexActionPost($input)
    {
        $this->request->setPost("input", $input);
        $this->request->setPost("timeMachine", "forecast");

        $res = $this->controller->indexActionPost();

        // Check the responce object
        $this->assertIsObject($res);
        $this->assertInstanceOf("\Anax\Response\Response", $res);
        $this->assertInstanceOf("\Anax\Response\ResponseUtility", $res);
    }
}
