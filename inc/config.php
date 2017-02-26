<?php
use hornherzogen\ConfigurationWrapper;

// Check whether the file exists or fallback to the template
// DEVHINT: it's quite odd that file_exists seems to start at root, while parse takes the relative path from this file
if (file_exists(dirname(__FILE__) . '/config.ini.php')) {
    $filename = 'config.ini.php';
} else {
    $filename = 'config.ini.php.template';
}
$isTemplate = strpos($filename, '.template') !== FALSE;

// load configuration and dump if template or debug=true
$GLOBALS["horncfg"] = parse_ini_file($filename);

$config = new ConfigurationWrapper();

if ($config->debug() || $isTemplate) {
    // allow error reporting
    ini_set('display_errors', 'on');
    ini_set('display_startup_errors', 'on');
    error_reporting(E_ALL);

    echo "<pre>";
    if ($isTemplate) {
        echo "<strong>You should adapt your configuration and save it as config.ini.php, currently the dummy template is used, which means NO database connection!</strong>";
    } else {
        echo $config;
    }
    echo "</pre>";
}

