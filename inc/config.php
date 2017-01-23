<?php
$GLOBALS["horncfg"] = parse_ini_file("config.ini.php");

if ($GLOBALS["horncfg"]["debug"]) {
    // allow error reporting
    ini_set('display_errors', 'on');
    ini_set('display_startup_errors', 'on');
    error_reporting(E_ALL);

    echo "<pre>";
    print_r($GLOBALS["horncfg"]);
    echo "</pre>";
}
?>


