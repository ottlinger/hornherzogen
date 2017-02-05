<?php
namespace hornherzogen;

use \MessageFormatter;

class HornLocalizer
{
    private static $fallbackLanguage = "de";

    /**
     * Retrieve language parameter if available with fallback to German version (de) by taking care of session state as well.
     * @return string
     */
    public static function getLanguage()
    {
        $sessionLanguage = self::getLanguageFromSession();
        $lang = self::getLanguageFromUrlParameter();

        // no URL parameter given
        if (empty($lang) && empty($sessionLanguage)) {
            return self::storeInSession(self::$fallbackLanguage);
        }

        // fallback from session
        if ((!isset($lang) || $lang === NULL) && isset($sessionLanguage)) {
            $lang = $sessionLanguage;
        }

        return self::checkIfValidOrReturnDefaultAndSetInSession($lang);
    }

    private static function checkIfValidOrReturnDefaultAndSetInSession($lang)
    {
        if (isset($lang) && !empty($lang)) {
            $lang = trim(strtolower($lang));
            switch ($lang) {
                case "de";
                case "en";
                case "ru";
                case "jp";
                    return self::storeInSession($lang);
            }
        }
        return self::storeInSession(self::$fallbackLanguage);
    }

    /**
     * Store given language in session.
     */
    private static function storeInSession($language)
    {
        $_SESSION['language'] = $language;
        return $language;
    }

    /**
     * @return null|string language setting from session store.
     */
    static function getLanguageFromSession()
    {
        return (isset($_SESSION) && isset($_SESSION['language'])) ? trim(filter_var($_SESSION['language'], FILTER_SANITIZE_STRING)) : NULL;
    }

    /**
     * @return null|string language setting from session store.
     */
    static function getLanguageFromUrlParameter()
    {
        // HINT: not working in tests:
        //  return (isset($_GET) && isset($_GET['lang'])) ? trim(filter_input(INPUT_GET, "lang", FILTER_SANITIZE_STRING)) : NULL;
        return (isset($_GET) && isset($_GET['lang'])) ? trim(filter_var($_GET['lang'], FILTER_SANITIZE_STRING)) : NULL;
    }


    public static function i18n($key)
    {
        $messageFormatter = new MessageFormatter(self::getLanguage(), $GLOBALS['messages'][self::getLanguage()][$key]);
        echo $messageFormatter->format(array());
    }

    public static function i18nParams($key, $params)
    {
        $messageFormatter = new MessageFormatter(self::getLanguage(), $GLOBALS['messages'][self::getLanguage()][$key]);
        echo $messageFormatter->format(array($params));
    }

}
