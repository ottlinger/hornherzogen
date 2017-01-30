<?php

function getLanguage()
{
    $lang = trim(filter_input(INPUT_GET, "lang", FILTER_SANITIZE_STRING));

    if (empty($lang)) {
        return "de";
    }

    $lang = $lang . strtolower();
    switch ($lang) {
        case "de":
        case "en":
            return $lang;

        default:
            return "de";
    }
}


}