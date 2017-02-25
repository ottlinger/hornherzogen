<?php
require '../vendor/autoload.php';

echo "<h1>Trying to check that localization with intl-extension is possible</h1>";

$messages = array();
$messages['de_DE'] = array('KEY_WITH_PARAM' => 'Meine besonderen Interessen liegen im Bereich {0} uvm.',
    'KEY_PLAIN' => 'Ohne Parameter kann doch wohl jeder');

$withParam = new MessageFormatter('de_DE', $messages['de_DE']['KEY_WITH_PARAM']);
$withoutParam = new MessageFormatter('de_DE', $messages['de_DE']['KEY_PLAIN']);

echo "<h1>Mit Parameter: " . $withParam->format(array("PHP und OpenSource, Tendoryu Aikido")) . "</h1>";
echo "<h1>Ohne Parameter: " . $withoutParam->format(array()) . "</h1>";

