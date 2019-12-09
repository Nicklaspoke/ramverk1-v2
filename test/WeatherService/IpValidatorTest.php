<?php

namespace Niko\WeatherService;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

class IpValidatorTest extends TestCase
{
    public function setUp()
    {
        $this->ipvalidator = new IpValidator();
    }

    public function validDataProvider()
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

    public function invalidDataProvider()
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
     * Test validate valid ipaddresses
     *
     * @dataProvider validDataProvider
     */
    public function testValidateIpValid($ip)
    {
        $res = $this->ipvalidator->validateIp($ip);

        $this->assertInternalType("array", $res);
        $this->assertEquals($ip, $res["ip"]);
        $this->assertEquals(true, $res["valid"]);
        $this->assertContains("ipv", $res["ip_version"]);
        $this->assertArrayHasKey("hostname", $res);
    }

    /**
     * Test validate for invalid ip addresses
     *
     * @dataProvider invalidDataProvider
     */
    public function testInvlidIpaddresses($ip)
    {
        $res = $this->ipvalidator->validateIp($ip);

        $this->assertInternalType("array", $res);
        $this->assertEquals($ip, $res["ip"]);
        $this->assertEquals(false, $res["valid"]);
    }
}
