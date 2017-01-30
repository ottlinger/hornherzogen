<?php
namespace hornherzogen;


class HornLocalizer
{
    private static $fallbackLanguage = "de";

    /**
     * Retrieve lang parameter if available with fallback to German version (de)
     * @return string
     */
    public static function getLanguage()
    {
        $lang = trim(filter_input(INPUT_GET, "lang", FILTER_SANITIZE_STRING));

        if (empty($lang)) {
            return HornLocalizer::$fallbackLanguage;
        }

        $lang = strtolower($lang);
        switch ($lang) {
            case "de";
            case "en";
                return $lang;
        }

        return HornLocalizer::$fallbackLanguage;
    }

}