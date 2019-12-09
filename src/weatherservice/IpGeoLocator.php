<?php

namespace Niko\WeatherService;

/**
 * Class that hanles communication with the Geo location api service
 */
class IpGeoLocator
{
    /**
     * Function that takes an ip and translates it into a geographical position
     *
     * @var string  $ip         A ipv4 or 6 address
     * @var array   $apiInfo    An assosiative array that holds baseUrl and apiKey
     *
     * @return array containing the lat and lan coordinates for the ip or false, if the ip couldn't be translated into a physical location
     */
    public function getGeoLocation($ip, $apiInfo)
    {
        $url = $apiInfo["baseUrl"] . $ip . "?access_key=" . $apiInfo["apiKey"];
        $res = file_get_contents($url);
        $res = json_decode($res, true);

        if ($res["latitude"] === null || $res["longitude"] === null) {
            return false;
        } else {
            return [
                "lat" => $res["latitude"],
                "lon" => $res["longitude"],
            ];
        }
        return $res;
    }
}
