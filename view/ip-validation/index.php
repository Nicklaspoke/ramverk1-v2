<br>
<br>
<h1>Ip Validation</h1>

<table>
    <tr>
        <th>kmom01</th>
        <th>kmom02</th>
    </tr>

    <tr>
        <td>
            <form method="POST">
                <label for="ipInput">Ipv4 or Ipv6 address to validate:</label>
                <input type="text" name="ipInput" value=<?= isset($userIp) ? $userIp : "8.8.8.8" ?>>
                <!-- <input type="submit" value="Validate"> -->
                <button name="kmom" type="submit" value="kmom01">Validate</button>
            </form>
        </td>

        <td>
            <form method="POST">
                <label for="ipInput">Ipv4 or Ipv6 address to validate:</label>
                <input type="text" name="ipInput" value=<?= isset($userIp) ? $userIp : "8.8.8.8" ?>>
                <!-- <input type="submit" value="Validate"> -->
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
