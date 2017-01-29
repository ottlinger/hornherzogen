<?php
require '../vendor/autoload.php';

echo "<p>Trying to check that localization is possible</p>";

$messages = array();
$messages['de_DE'] = array('KEY_WITH_PARAM' => 'Meine besonderen Interessen liegen im Bereich {0} uvm.',
    'KEY_PLAIN' => 'Ohne Parameter kann doch wohl jeder');

$withParam = new MessageFormatter('de_DE', $messages['de_DE']['KEY_WITH_PARAM']);
$withoutParam = new MessageFormatter('de_DE', $messages['de_DE']['KEY_PLAIN']);

echo "<h1>Mit Parameter: ".$withParam->format(array("PHP und OpenSource, Tendoryu Aikido"))."</h1>";

