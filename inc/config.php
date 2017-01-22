<?php
$GLOBALS["horncfg"] = parse_ini_file("config.ini.php");

if ($GLOBALS["horncfg"]["debug"]) {
    echo "<pre>";
    print_r($GLOBALS["horncfg"]);
    echo "</pre>";
}
?>


