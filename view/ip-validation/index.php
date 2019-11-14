<br>
<br>
<h1>Ip Validation</h1>

<form method="POST">
    <label for="ipInput">Ipv4 or Ipv6 address to validate:</label>
    <input type="text" name="ipInput" value="8.8.8.8">
    <input type="submit" value="Validate">
</form>

<?php
if (!empty($message)) {
echo "<div class='box-message'>";
        echo $message;
echo "</div>";
}
?>
