<?php

require '../../vendor/autoload.php';

echo '<h1>Trying to check that localization with intl-extension is possible</h1>';

$messages = [];
$messages['de_DE'] = ['KEY_WITH_PARAM' => 'Meine besonderen Interessen liegen im Bereich {0} und {1} uvm.',
    'KEY_PLAIN'                        => 'Ohne Parameter kann doch wohl jeder', ];

$withParam = new MessageFormatter('de_DE', $messages['de_DE']['KEY_WITH_PARAM']);
$withoutParam = new MessageFormatter('de_DE', $messages['de_DE']['KEY_PLAIN']);

echo '<h1>Mit Parameter: '.$withParam->format(['PHP und OpenSource, Tendoryu Aikido', 'Parameter']).'</h1>';
echo '<h1>Ohne Parameter: '.$withoutParam->format([]).'</h1>';

// TBD: add health check / statistics about l10n
