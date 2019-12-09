<?php

namespace Niko\WeatherService;

/**
 * Class for validating ip addresses
 */
class IpValidator
{
    /**
     * Takes an input and checks if it's a valid ipv4 or ipv6 address
     *
     * @var string $ip  The inputed ip or string to validate
     *
     * @return array
     */
    public function validateIp($ip)
    {
        $returnData = [];

        $returnData["ip"] = $ip;

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) != false) {
            $returnData["valid"] = true;
            $returnData["ip_version"] = "ipv4";
            $returnData["hostname"] = gethostbyaddr($ip);
        } elseif (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) != false) {
            $returnData["valid"] = true;
            $returnData["ip_version"] = "ipv6";
            $returnData["hostname"] = gethostbyaddr($ip);
        } else {
            $returnData["valid"] = false;
        }

        return $returnData;
    }
}
