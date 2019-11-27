<?php

namespace Niko\ApiInfo;


use PHPUnit\Framework\TestCase;

class apiInfoTest extends TestCase
{
    /**
     * SetUp the class for the testcases
     */
    public function setup()
    {
        $this->apiInfo = new ApiInfo();
    }

    public function testConstructor()
    {
        $this->assertIsObject($this->apiInfo);
    }

    public function testGetAndSetApiKey()
    {
        $this->apiInfo->setApiKey("test", "http://127.0.0.1", "123456789");

        $info = $this->apiInfo->getApiKey("test");

        $this->assertInternalType("array", $info);
        $this->assertEquals("http://127.0.0.1", $info["baseUrl"]);
        $this->assertEquals("123456789", $info["apiKey"]);
    }
}
