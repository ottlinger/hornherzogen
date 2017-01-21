<?php
echo "<pre>";
$GLOBALS["horncfg"] = parse_ini_file("config.ini.php");
print_r($GLOBALS["horncfg"]);
echo "</pre>";
?>


