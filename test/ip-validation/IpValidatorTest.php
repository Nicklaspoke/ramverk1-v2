<?php

namespace Niko\IpValidation;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Testing clas for testing the ipvalidator class
 */
class ipvalidatorTest extends TestCase
{
    /**
     * Setup for the testcases
     */
    public function setup()
    {
        $this->validator = new IpValidator;
    }

    public function validIpProvider()
    {
        return [
            ["127.0.0.1"],
            ["8.8.8.8"],
            ["1.1.1.1"],
            ["192.168.0.1"],
            ["194.47.150.9"],
            ["2606:4700:20:681b::490c"],
            ["2001:0DBB:AC10:FE01::000"],
            ["2a00:1450:4013:c00::66"],
            ["2001:db8:3333:4444:5555:6666:7777:8888"],
            ["2001:db8:3333:4444:CCCC:DDDD:EEEE:FFFF"]
        ];
    }

    public function invalidIpProvider()
    {
        return [
            ["1.1.300.2"],
            ["300.300.300.300"],
            ["-1.25.36.1"],
            ["-1.-2.-3.-4"],
            ["256.255.221"],
            ["2606:4700:20::GF45::01"],
            ["FFFF:EEEE:3333::::"],
            ["2606:4700:20::GF45::0G"],
            ["2606:4700:20PQ::GF45::01"],
            ["1111::2222::3333::FED3"],
        ];
    }

    /**
     * Test the validator with a range of valid ip adresses
     *
     * @dataProvider validIpProvider
     */
    public function testValidateIpValidAdress($ip)
    {
        $data = $this->validator->validateIp($ip);

        $this->assertContains($ip, $data["ip"]);
        $this->assertEquals(true, $data["valid"]);
    }

    /**
     * Test the validator with a range of valid ip adresses
     *
     * @dataProvider invalidIpProvider
     */
    public function testValidateIpinvalidAdress($ip)
    {
        $data = $this->validator->validateIp($ip);

        $this->assertContains($ip, $data["ip"]);
        $this->assertEquals(false, $data["valid"]);
    }

    /**
     * Testing the geolocation service.
     *
     * Only doing one test, cause the api-call restriction on the key
     */
    public function testGeoLocation()
    {
        $res = $this->validator->getGeoLocation("8.8.8.8");

        $this->assertEquals("8.8.8.8", $res["ip"]);
        $this->assertEquals("United States", $res["country_name"]);
        $this->assertEquals("37.419158935546875", $res["latitude"]);
        $this->assertEquals("-122.07540893554688", $res["longitude"]);

    }
}
