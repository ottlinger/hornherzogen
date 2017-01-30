<?php
namespace hornherzogen;
use \MessageFormatter;

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
            case "ru";
                return $lang;
        }

        return HornLocalizer::$fallbackLanguage;
    }

    public static function i18n($key)
    {
         $mf = new MessageFormatter(self::getLanguage(), $GLOBALS['messages'][self::getLanguage()][$key]);
         echo $mf->format(array());
    }

    public static function i18nParams($key, $params)
    {
        $mf = new MessageFormatter(self::getLanguage(), $GLOBALS['messages'][self::getLanguage()][$key]);
        echo $mf->format(array($params));
    }

}