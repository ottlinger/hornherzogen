<?php

// Check whether the file exists or fallback to the template
if (file_exists('config.ini.php')) {
    $filename = 'config.ini.php';
} else {
    $filename = 'config.ini.php.template';
}
$isTemplate = strpos($filename, '.template') !== FALSE;

// load configuration and dump if template or debug=true
$GLOBALS["horncfg"] = parse_ini_file('config.ini.php');
if ($GLOBALS["horncfg"]["debug"] || $isTemplate) {
    // allow error reporting
    ini_set('display_errors', 'on');
    ini_set('display_startup_errors', 'on');
    error_reporting(E_ALL);

    echo "<pre>";
    if ($isTemplate) {
        echo "<strong>You should adapt your configuration and save it as config.ini.php, currently the dummy template is used.</strong>";
    }

    print_r($GLOBALS["horncfg"]);
    echo "</pre>";
}
?>


