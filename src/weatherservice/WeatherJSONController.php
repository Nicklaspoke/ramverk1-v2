<?php

namespace Niko\WeatherService;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

/**
 * Class controller for handling the json api calls to the weather service
 */
class WeatherJSONController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    public function initialize() : void
    {
        $this->title = "Weather Service JSON API";
        $this->weatherService = new WeatherService();
        $this->ipValidator = new IpValidator();
        $this->geolocator = new IpGeoLocator();
    }

    public function indexActionGet()
    {
        $page = $this->di->get("page");
        $page->add("weather-service/index-json");

        return $page->render([
            "title" => $this->title
        ]);
    }

    public function jsonActionGet()
    {
        $input = $this->di->get("request")->getGet("input");
        $option = $this->di->get("request")->getGet("option");

        $location = [];

        if (strpos($input, ",") !== false) {
            $res = explode(",", $input);
            $location["lat"] = $res[0];
            $location["lon"] = $res[1];
        } else {
            $res = $this->ipValidator->validateIp($input);
            if ($res["valid"] === false) {
                $json = [
                    "message" => "Invalid ip or no geolocation for provided ip",
                ];
            } else {
                $res = $this->geolocator->getGeoLocation($input, $this->di->get("apiInfo")->getApiKey("ipstack"));
                if ($res["lat"] === null) {
                    $json = [
                        "message" => "Invalid ip or no geolocation for provided ip",
                    ];
                } else {
                    $location["lat"] = $res["lat"];
                    $location["lon"] = $res["lon"];
                }
            }
        }

        if (isset($location["lat"])) {
            $json["geoData"] = $location;
            $json["weatherData"] = $this->weatherService->getWeatherData($location, $option, $this->di->get("apiInfo")->getApiKey("darkSky"));
        }

        return [$json];
    }
}
