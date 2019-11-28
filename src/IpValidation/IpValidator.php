<?php

namespace Niko\IpValidation;

/**
 * Clas that validates a given ip
 * returns if ip is valid, which domain/hostname if it has one
 * geolocation if possible
 */
class IpValidator
{
    /**
     * Takes a ip and validates it.
     * returns an array with info about hostname and ip version
     */
    public function validateIp($ip) : array
    {
        $returnData = [];

        $returnData["ip"] = $ip;

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) != false) {
            $returnData["valid"] = true;
            $returnData["ip_version"] = "ipv4";
        } elseif (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) != false) {
            $returnData["valid"] = true;
            $returnData["ip_version"] = "ipv6";
        } else {
            $returnData["valid"] = false;
        }

        if ($returnData["valid"]) {
            $returnData["hostname"] = gethostbyaddr($ip);
        }

        return $returnData;
    }

    public function getGeoLocation($ip, $apiInfo)
    {
        // include("apiKey.php");
        $url = $apiInfo["baseUrl"] . $ip . "?access_key=" . $apiInfo["apiKey"];
        $res = file_get_contents($url);
        $res = json_decode($res, true);

        return $res;
    }
}
