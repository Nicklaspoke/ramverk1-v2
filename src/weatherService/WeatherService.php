<?php

namespace Niko\WeatherService;

class WeatherService
{
    public function __construct()
    {
        $this->ipValidator = new \Niko\IpValidation\IpValidator();
    }

    /**
     * Function that takes the different inputs and returns
     * either the forecast/s or false if the ip is not valid or
     * no geoInfo for the iå exists
     *
     * @var string $input   The ipnuted ip or lat and lon coordinates
     * @var string $option  The option the user choose, about forecast or previous weather
     * @var object $apiInfo Object retrived from di, for acess to apiKeys
     */
    public function getWeatherData($input, $option, $apiInfo)
    {
        $geodata = [];

        if (strpos($input, ",") !== false) {
            $res = explode(",", $input);
            $geodata["lat"] = $res[0];
            $geodata["lon"] = $res[1];
        } elseif (filter_var($input, FILTER_VALIDATE_IP) != false) {
            $res = $this->ipValidator->getGeoLocation($input, $apiInfo->getApiKey("ipstack"));
            if ($res["latitude"] === null) {
                return false;
            }
            $geodata["lat"] = $res["latitude"];
            $geodata["lon"] = $res["longitude"];
        } else {
            return false;
        }

        if ($option === "forecast") {
            $data = $this->getForecast($geodata, $apiInfo->getApiKey("darkSky"));
            $data = $this->formatData($data);
        } elseif ($option === "previous") {

        }


        return $data;
    }

    public function getForecast($geodata, $apiInfo) : array
    {
        $url = $apiInfo["baseUrl"] . $apiInfo["apiKey"] . "/";
        $url .= $geodata["lat"] . "," . $geodata["lon"];
        $url .= "?exclude=hourly,minutely,alerts,flags&units=si";
        echo $url;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);

        $data = json_decode(curl_exec($ch), true);

        curl_close($ch);

        return $data;
    }

    public function getPrevipusWeather($geodata) : array
    {

    }

    private function formatData($weatherData) : array
    {
        $dayData = $weatherData["daily"];
        $days = $dayData["data"];
        $data = [];

        foreach ($days as $daysData) {
            $day = date("d/m", $daysData["time"]);
            $sunrise = date("H:i:s", $daysData["sunriseTime"]);
            $sundown = date("H:i:s", $daysData["sunsetTime"]);
            $data[$day] = [
                "summary" => $daysData["summary"],
                "sunrise" => $sunrise,
                "sunset" => $sundown,
                "tempetureHigh" => $daysData["temperatureHigh"],
                "tempetureLow" => $daysData["temperatureLow"],
                "humidity" => $daysData["humidity"],
                "pressure" => $daysData["pressure"],
                "windSpeed" => $daysData["windSpeed"],
            ];
        }

        return $data;
    }
}
