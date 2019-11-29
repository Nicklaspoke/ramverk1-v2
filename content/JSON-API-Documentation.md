---
title: "JSON API Documentaion"
---
# About the API

This is a REST-API. That can validate ip address and give info about theier verison, location. It also offers a weather service for getting the weather via a ip-address or a
physical location by lat and lon cooredinets.

## Calls related to IP validation and geolocations

### Only Ip validation and hostname
To only validate an ip and not getting any geoinfo.
```
/validate-ip-json/json?ip=8.8.8.8&kmom=kmom01
```

If the ip is valid the returned json will be:
```
{
    "ip": "8.8.8.8",
    "valid": true,
    "ip_version": "ipv4",
    "hostname": "dns.google"
}
```

If the ip is not valid the responce will be:
```
{
    "ip": "300.0.0.0",
    "valid": false
}
```

### Get Ip validation and geolocation
If you also want the physical geo location for the Ip the call is:
```
/validate-ip-json/json?ip=8.8.8.8&kmom=kmom02
```

If the ip is valid and can be translated into a geolocation the returned json will be:
```
{
    "ip": "8.8.8.8",
    "valid": true,
    "ip_version": "ipv4",
    "hostname": "dns.google",
    "geoinfo": {
        "ip": "8.8.8.8",
        "type": "ipv4",
        "continent_code": "NA",
        "continent_name": "North America",
        "country_code": "US",
        "country_name": "United States",
        "region_code": "CA",
        "region_name": "California",
        "city": "Mountain View",
        "zip": "94043",
        "latitude": 37.419158935546875,
        "longitude": -122.07540893554688,
        "location": {
            "geoname_id": 5375480,
            "capital": "Washington D.C.",
            "languages": [
                {
                    "code": "en",
                    "name": "English",
                    "native": "English"
                }
            ],
            "country_flag": "http://assets.ipstack.com/flags/us.svg",
            "country_flag_emoji": "\ud83c\uddfa\ud83c\uddf8",
            "country_flag_emoji_unicode": "U+1F1FA U+1F1F8",
            "calling_code": "1",
            "is_eu": false
        }
    }
}
```

If the ip is valid but can't be translated to a physical location it will be:
```
{
    "ip": "127.0.0.1",
    "valid": true,
    "ip_version": "ipv4",
    "hostname": "kubernetes.docker.internal",
    "geoinfo": {
        "ip": "127.0.0.1",
        "type": null,
        "continent_code": null,
        "continent_name": null,
        "country_code": null,
        "country_name": null,
        "region_code": null,
        "region_name": null,
        "city": null,
        "zip": null,
        "latitude": null,
        "longitude": null,
        "location": {
            "geoname_id": null,
            "capital": null,
            "languages": null,
            "country_flag": null,
            "country_flag_emoji": null,
            "country_flag_emoji_unicode": null,
            "calling_code": null,
            "is_eu": null
        }
    }
}
```

If an Ip is invalid, the responce will be the same as in the other call.

## Calls for weather service
If you would like to use the api for the weather service:

A call should contain the following parameters:

* input: the Ip address or lat and lon
* option: forecast or previous

The ip should be a standard ipv4 or ipv6 address. If you would like to use lat and lon coordinates it should be in the form "lat,lon".

In the option parameter, forecast will give the weather forecast fot the comming 8 days and previous will give the last 30 days of weather.

The call:
```
/weather-json/json?input=8.8.8.8&option=forecast
```

If the Ip or lat,lon is valid the responce will be:
```
{
    "geoData": {
        "lat": 37.419158935546875,
        "lon": -122.07540893554688
    },
    "weatherData": {
        "29/11": {
            "summary": "Partly cloudy throughout the day.",
            "sunrise": "16:04:00",
            "sunset": "01:53:00",
            "tempetureHigh": 11.67,
            "tempetureLow": 4.16,
            "humidity": 0.75,
            "pressure": 1010.7,
            "windSpeed": 1.72
        },
        "30/11": {
            "summary": "Light rain throughout the day.",
            "sunrise": "16:05:00",
            "sunset": "01:52:00",
            "tempetureHigh": 11.18,
            "tempetureLow": 10.31,
            "humidity": 0.81,
            "pressure": 1014.2,
            "windSpeed": 5.36
        },
        "01/12": {
            "summary": "Light rain throughout the day.",
            "sunrise": "16:06:00",
            "sunset": "01:52:00",
            "tempetureHigh": 14.66,
            "tempetureLow": 11.85,
            "humidity": 0.86,
            "pressure": 1012.5,
            "windSpeed": 5.84
        },
        "02/12": {
            "summary": "Light rain throughout the day.",
            "sunrise": "16:07:00",
            "sunset": "01:52:00",
            "tempetureHigh": 16.53,
            "tempetureLow": 11.79,
            "humidity": 0.86,
            "pressure": 1016.7,
            "windSpeed": 4.29
        },
        ...
        }
}
```

IF the Ip or lat,lon is Invalid the responce will be:
```
{
    "message": "Invalid ip, no geolocation for provided ip or invalid lat and lon"
}
```
