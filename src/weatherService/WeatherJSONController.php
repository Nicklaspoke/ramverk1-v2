<?php

namespace Niko\WeatherService;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

class WeatherJSONController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    public function initialize() : void
    {
        $this->title = "Weather Service JSON API";
        $this->weatherService = new WeatherService();
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

        $res = $this->weatherService->getWeatherData($input, $option, $this->di->get("apiInfo"));

        if ($res === false) {
            $json = [
                "message" => "Invalid ip, no geolocation for provided ip or invalid lat and lon",
            ];
        } else {
            $json = [
                "geoData" => $res["geoData"],
                "weatherData" => $res["weatherData"],
            ];
        }

        return [$json];
    }

}
