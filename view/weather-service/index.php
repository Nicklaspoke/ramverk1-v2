<br>
<br>
<h1>Weather Service</h1>

<form method="POST">
    <label for="input">Ip adress or latitude and longitude seperated by a coma (lat, lon):</label><br>
    <input tyoe="text" name="input">
    <br>
    <label for="timeMachine">Forecast or previous weather:</label>
    <br>
    <input type="radio" name="timeMachine" value="forecast">Forecast<br>
    <input type="radio" name="timeMachine" value="previous">Previous Weather

    <input type="submit" value="submit">
</form>

<?php if (isset($message)): ?>
    <div class="box-message">
        <?= $message ?>
    </div>

<?php elseif (!empty($weatherData)): ?>

<table>
    <tr>
        <th>Day</th>
        <th>Summary</th>
        <th>Sunrise</th>
        <th>Sunset</th>
        <th>Highest Temp (°C)</th>
        <th>Lowest Temp (°C)</th>
        <th>Humidity</th>
        <th>Pressure</th>
        <th>Windspeed (Km/h)</th>
    </tr>

    <?php foreach ($weatherData as $day => $dayData): ?>
        <tr>
            <td>
                <?= $day ?>
            </td>
            <td>
                <?= $dayData["summary"] ?>
            </td>
            <td>
                <?= $dayData["sunrise"] ?>
            </td>
            <td>
                <?= $dayData["sunset"] ?>
            </td>
            <td>
                <?= $dayData["tempetureHigh"] ?>
            </td>
            <td>
                <?= $dayData["tempetureLow"] ?>
            </td>
            <td>
                <?= $dayData["humidity"] ?>
            </td>
            <td>
                <?= $dayData["pressure"] ?>
            </td>
            <td>
                <?= $dayData["windSpeed"] ?>
            </td>
        </tr>
    <?php endforeach ?>
</table>
<?php endif ?>
