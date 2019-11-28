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
        $data = [];

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
            $res = $this->getForecast($geodata, $apiInfo->getApiKey("darkSky"));
            if (isset($res["code"]) && $res["code"] == 400) {
                return false;
            }
            $data["weatherData"] = $this->formatForecastData($res);
            $data["mapCoords"] = $this->getMapData($geodata);
            $data["geoData"] = $geodata;
        } elseif ($option === "previous") {
            $res = $this->getPrevipusWeather($geodata, $apiInfo->getApiKey("darkSky"));
            if (isset($res["code"]) && $res["code"] == 400) {
                return false;
            }
            $data["weatherData"] = $this->formatHistoricalData($res);
            $data["mapCoords"] = $this->getMapData($geodata);
            $data["geoData"] = $geodata;
        }


        return $data;
    }

    public function getForecast($geodata, $apiInfo) : array
    {
        $url = $apiInfo["baseUrl"] . $apiInfo["apiKey"] . "/";
        $url .= $geodata["lat"] . "," . $geodata["lon"];
        $url .= "?exclude=currently,hourly,minutely,alerts,flags&units=si";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);

        $data = json_decode(curl_exec($ch), true);

        curl_close($ch);

        return $data;
    }

    public function getPrevipusWeather($geodata, $apiInfo) : array
    {
        $url = $apiInfo["baseUrl"] . $apiInfo["apiKey"] . "/";
        $url .= $geodata["lat"] . "," . $geodata["lon"];
        $urlOptions = "?exclude=currently,hourly,minutely,alerts,flags&units=si";

        $options = [
            CURLOPT_RETURNTRANSFER => true
        ];

        $mh = curl_multi_init();
        $chAll = [];

        for ($i = 1; $i <= 30; $i++) {
            $time = "-" . $i . "days";
            $sendUrl = $url . "," . strtotime($time) . $urlOptions;
            $ch = curl_init($sendUrl);
            curl_setopt_array($ch, $options);
            curl_multi_add_handle($mh, $ch);
            $chAll[] = $ch;
        }

        $running = null;
        do {
            curl_multi_exec($mh, $running);
        } while ($running);

        foreach ($chAll as $ch) {
            curl_multi_remove_handle($mh, $ch);
        }

        curl_multi_close($mh);

        $responce = [];
        foreach ($chAll as $ch) {
            $data = curl_multi_getcontent($ch);
            $responce[] = json_decode($data, true);
        }

        return $responce;
    }

    private function formatForecastData($weatherData) : array
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

    private function formatHistoricalData($weatherData)
    {
        $data = [];

        foreach ($weatherData as $daysData) {
            $dayData = $daysData["daily"];
            $dayData = $dayData["data"];
            $dayData = $dayData[0];

            $time = date("d/m", $dayData["time"]);
            $sunrise = date("H:i:s", $dayData["sunriseTime"]);
            $sundown = date("H:i:s", $dayData["sunsetTime"]);
            $data[$time] = [
                "summary" => $dayData["summary"],
                "sunrise" => $sunrise,
                "sunset" => $sundown,
                "tempetureHigh" => $dayData["temperatureHigh"],
                "tempetureLow" => $dayData["temperatureLow"],
                "humidity" => $dayData["humidity"],
                "pressure" => $dayData["pressure"],
                "windSpeed" => $dayData["windSpeed"],
            ];
        }

        return $data;
    }

    public function getMapData($geodata)
    {
        $possistions = [];
        $possistions["bottomRight"] = strval(floatval($geodata["lon"]) - 0.5) . "%2c" . strval(floatval($geodata["lat"]) - 0.5);
        $possistions["topLeft"] = strval(floatval($geodata["lon"]) + 0.5) . "%2c" . strval(floatval($geodata["lat"]) + 0.5);

        return $possistions;
    }
}
