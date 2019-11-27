<br>
<br>
<h1>Ip Validation</h1>

<h2>Hard coded testlinks for JSON</h2>

<table>
    <tr>
        <th>kmom01</th>
        <th>kmom02</th>
    </tr>

    <tr>
        <td>
        <ul>
            <li>
                <a href="validate-ip-json/json?ip=8.8.8.8&kmom=kmom01">8.8.8.8</a>
            </li>

            <li>
                <a href="validate-ip-json/json?ip=1.1.1.1&kmom=kmom01">1.1.1.1</a>
            </li>

            <li>
                <a href="validate-ip-json/json?ip=127.0.0.1&kmom=kmom01">127.0.0.1</a>
            </li>

            <li>
                <a href="validate-ip-json/json?ip=194.47.150.9&kmom=kmom01">194.47.150.9</a>
            </li>

            <li>
                <a href="validate-ip-json/json?ip=158.174.62.146&kmom=kmom01">158.174.62.146</a>
            </li>

            <li>
                <a href="validate-ip-json/json?ip=2606:4700:20::681b:490c&kmom=kmom01">2606:4700:20::681b:490c</a>
            </li>
        </ul>
        </td>

        <td>
            <ul>
                <li>
                    <a href="validate-ip-json/json?ip=8.8.8.8&kmom=kmom02">8.8.8.8</a>
                </li>

                <li>
                    <a href="validate-ip-json/json?ip=1.1.1.1&kmom=kmom02">1.1.1.1</a>
                </li>

                <li>
                    <a href="validate-ip-json/json?ip=127.0.0.1&kmom=kmom02">127.0.0.1</a>
                </li>

                <li>
                    <a href="validate-ip-json/json?ip=194.47.150.9&kmom=kmom02">194.47.150.9</a>
                </li>

                <li>
                    <a href="validate-ip-json/json?ip=158.174.62.146&kmom=kmom02">158.174.62.146</a>
                </li>

                <li>
                    <a href="validate-ip-json/json?ip=2606:4700:20::681b:490c&kmom=kmom02">2606:4700:20::681b:490c</a>
                </li>
            </ul>
        </td>
    </tr>

    <tr>
        <td>
            <form method="GET" action="validate-ip-json/json">
                <label for="ip">Ipv4 or Ipv6 address to validate:</label>
                <input type="text" name="ip" value=<?= isset($userIp) ? $userIp : "8.8.8.8" ?>>
                <button name="kmom" type="submit" value="kmom01">Validate</button>
            </form>
        </td>

        <td>
            <form method="GET" action="validate-ip-json/json">
                <label for="ip">Ipv4 or Ipv6 address to validate:</label>
                <input type="text" name="ip" value=<?= isset($userIp) ? $userIp : "8.8.8.8" ?>>
                <button name="kmom" type="submit" value="kmom02">Validate</button>
            </form>
        </td>
    </tr>
</table>




<?php
if (!empty($message)) {
    echo "<div class='box-message'>";
        echo $message;
    echo "</div>";
}
?>
