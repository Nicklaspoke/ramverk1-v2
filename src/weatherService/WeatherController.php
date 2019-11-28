<?php

namespace Niko\WeatherService;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

class WeatherController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    public function initialize() : void
    {
        $this->title = "Weather Service";
        $this->weatherService = new WeatherService();
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
        $apiInfo = $this->di->get("apiInfo");

        $weatherData = $this->weatherService->getWeatherData($input, $option, $apiInfo);
        if ($weatherData === false) {
            $data["message"] = "Invalid ip, no geolocation for provided ip or invalid lat and lon";
        } else {
            $data["weatherData"] = $weatherData;
        }

        $page = $this->di->get("page");
        $page->add("weather-service/index", $data);

        return $page->render([
            "title" => $this->title
        ]);
    }
}
