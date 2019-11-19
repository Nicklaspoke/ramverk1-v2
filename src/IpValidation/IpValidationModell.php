<?php

namespace Niko\IpValidation;

/**
 * Clas that validates a given ip
 * returns if ip is valid, which domain/hostname if it has one
 * geolocation if possible
 */
class Ipvalidator
{
    /**
     * Takes a ip and validates it.
     * returns an array with info about hostname and ip version
     */
    public function validateIp($ip) : array
    {
        $returnData = [];

        $returnData["ip"] = $ip;

        if (filter_var($ip, FILTER_FLAG_IPV4)) {
            $returnData["valid"] = true;
            $returnData["ip_type"] = "ipv4";
        } elseif (filter_var($ip, FILTER_FLAG_IPV6)) {
            $returnData["valid"] = true;
            $returnData["ip_type"] = "ipv6";
        } else {
            $returnData["valid"] = false;
        }

        if ($returnData["valid"]) {
            $returnData["hostname"] = gethostbyaddr($ip);
        }
    }
}
