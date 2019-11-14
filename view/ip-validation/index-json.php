<br>
<br>
<h1>Ip Validation</h1>

<h2>Hard coded testlinks for JSON</h2>

<ul>
    <li>
        <a href="validate-ip-json/json?ip=8.8.8.8">8.8.8.8</a>
    </li>

    <li>
        <a href="validate-ip-json/json?ip=1.1.1.1">1.1.1.1</a>
    </li>

    <li>
        <a href="validate-ip-json/json?ip=127.0.0.1">127.0.0.1</a>
    </li>

    <li>
        <a href="validate-ip-json/json?ip=194.47.150.9">194.47.150.9</a>
    </li>

    <li>
        <a href="validate-ip-json/json?ip=158.174.62.146">158.174.62.146</a>
    </li>

    <li>
        <a href="validate-ip-json/json?ip=2606:4700:20::681b:490c">2606:4700:20::681b:490c</a>
    </li>
</ul>

<form method="GET" action="validate-ip-json/json">
    <label for="ip">Ipv4 or Ipv6 address to validate:</label>
    <input type="text" name="ip" value="8.8.8.8">
    <input type="submit" value="Validate">
</form>

<?php
if (!empty($message)) {
echo "<div class='box-message'>";
        echo $message;
echo "</div>";
}
?>
