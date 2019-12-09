<br>
<br>
<h1>Weather Service</h1>

<h2>Hard coded testlink for the JSON API</h2>

<ul>
    <li>
        <a href="weather-json/json?input=8.8.8.8&option=forecast">8.8.8.8 With current weather</a>
    </li>
    <li>
        <a href="weather-json/json?input=8.8.8.8&option=previous">8.8.8.8 With previous weather</a>
    </li>

    <li>
        <a href="weather-json/json?input=194.47.129.126&option=forecast">194.47.129 With current weather</a>
    </li>
    <li>
        <a href="weather-json/json?input=194.47.129.126&option=previous">194.47.129 With previous weather</a>
    </li>

    <li>
        <a href="weather-json/json?input=56.172,15.601&option=forecast">56.172,15.601 With current weather</a>
    </li>
    <li>
        <a href="weather-json/json?input=56.172,15.601&option=previous">56.172,15.601 With previous weather</a>
    </li>
</ul>

<form method="GET" action="weather-json/json">
    <label for="input">Ip adress or latitude and longitude seperated by a coma (lat, lon):</label><br>
    <input tyoe="text" name="input">
    <br>
    <label for="option">Forecast or previous weather:</label>
    <br>
    <input type="radio" name="option" value="forecast">Forecast<br>
    <input type="radio" name="option" value="previous">Previous Weather

    <input type="submit" value="submit">
</form>
