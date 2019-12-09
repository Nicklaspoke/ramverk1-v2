<?php

namespace Niko\WeatherService;

class WeatherService
{
    /**
     * Main function to handle to weather service requests
     *
     * @var array   $location   Array that holds the lat and lan coordinates
     * @var string  $option     String with the option to se previous weather or a forecast for the location
     * @var array   $apiInfo    Array that holds the apiInfo for the darkSky API
     *
     * @return array containgin the weather data, if invlid option return array with a error message
     */
    public function getWeatherData($location, $option, $apiInfo)
    {
        $data = [];

        if ($option === "forecast") {
            $res = $this->getForecast($location, $apiInfo);

            if (isset($res["code"]) && $res["code"] == 400) {
                return [
                    "message" => "Invalid lat or lon coordinates",
                ];
            }

            $data["weatherData"] = $this->formatForecastData($res);
        } elseif ($option === "previous") {
            $res = $this->getPreviousWeather($location, $apiInfo);

            if (isset($res[0]["code"]) && $res[0]["code"] == 400) {
                return [
                    "message" => "Invalid lat or lon coordinates",
                ];
            }

            $data["weatherData"] = $this->formatHistoricalData($res);
        } else {
            return [
                "message" => "Invalid option parameter",
            ];
        }

        $data["mapCoords"] = $this->getMapData($location);
        $data["geoData"] = $location;

        return $data;
    }

    /**
     * Contacts the external api servce (darksky API), to get the weatherforecast
     * for the given location
     *
     * @var array   $location   array containing lat and lon coordinates
     * @var array   $apiInfo    array containgin info for contacting the external weather serviece
     *
     * @return array with the weather data
     */
    private function getForecast($location, $apiInfo)
    {
        $url = $apiInfo["baseUrl"] . $apiInfo["apiKey"] . "/";
        $url .= $location["lat"] . "," . $location["lon"];
        $url .= "?exclude=currently,hourly,minutely,alerts,flags&units=si";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);

        $data = json_decode(curl_exec($ch), true);

        curl_close($ch);

        return $data;
    }

    /**
     * Contacts the external api servce (darksky API), to get the weatherforecast
     * of the previous 30 days
     *
     * @var array   $location   array containgin location coordinetes
     * @var array   $apiInfo    array containgin info for contacting the external weather serviece
     *
     * @return array with the weather data
     */
    private function getPreviousWeather($location, $apiInfo)
    {
        $url = $apiInfo["baseUrl"] . $apiInfo["apiKey"] . "/";
        $url .= $location["lat"] . "," . $location["lon"];
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

    /**
     * Takes the outputed forecast data and formats it into the info that
     * will be presented in the user view
     *
     * @var array   With the raw weather data
     *
     * @return array    with formated data
     */
    private function formatForecastData($weatherData)
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

    /**
     * Takes the historical data of the proevious 30 days and formats it into
     * the info that will be presented in the user view
     *
     * @var array   With the raw weather data
     *
     * @return array    With the formated data
     */
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

    private function getMapData($location)
    {
        $possistions = [];
        $possistions["bottomRight"] = strval(floatval($location["lon"]) - 0.5) . "%2c" . strval(floatval($location["lat"]) - 0.5);
        $possistions["topLeft"] = strval(floatval($location["lon"]) + 0.5) . "%2c" . strval(floatval($location["lat"]) + 0.5);

        return $possistions;
    }
}
