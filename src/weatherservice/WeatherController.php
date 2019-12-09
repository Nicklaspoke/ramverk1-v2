<?php

namespace Niko\WeatherService;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

/**
 * Class controller for handling the web requests to the weather service
 */
class WeatherController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    public function initialize() : void
    {
        $this->title = "Weather Service";
        $this->weatherService = new WeatherService();
        $this->ipValidator = new IpValidator();
        $this->geolocator = new IpGeoLocator();
    }

    public function indexActionGet()
    {
        $page = $this->di->get("page");
        $page->add("weather-service/index");

        return $page->render([
            "title" => $this->title
        ]);
    }

    public function indexActionPost()
    {
        $data = [];
        $data["title"] = $this->title;

        $input = $this->di->get("request")->getPost("input");
        $option = $this->di->get("request")->getPost("timeMachine");

        if (strpos($input, ",") !== false) {
            $res = explode(",", $input);
            $location["lat"] = $res[0];
            $location["lon"] = $res[1];
        } else {
            $res = $this->ipValidator->validateIp($input);
            if ($res["valid"] === false) {
                $data["message"] = "Invalid ip";
            } else {
                $res = $this->geolocator->getGeoLocation($input, $this->di->get("apiInfo")->getApiKey("ipstack"));
                if ($res["lat"] === null) {
                    $data["message"] = "No geolocation for provided ip";
                } else {
                    $location["lat"] = $res["lat"];
                    $location["lon"] = $res["lon"];
                }
            }
        }

        if (isset($location["lat"])) {
            $data["geoData"] = $location;
            $res = $this->weatherService->getWeatherData($location, $option, $this->di->get("apiInfo")->getApiKey("darkSky"));
            if (isset($res["message"])) {
                $data["message"] = $res["message"];
            } else {
                $data["weatherData"] = $res["weatherData"];
                $data["mapCoords"] = $res["mapCoords"];
                $data["geoData"] = $res["geoData"];
            }
        }

        $page = $this->di->get("page");
        $page->add("weather-service/index", $data);

        return $page->render([
            "title" => $this->title
        ]);
    }
}
