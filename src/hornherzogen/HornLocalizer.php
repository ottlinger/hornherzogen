<?php
namespace hornherzogen;

use MessageFormatter;

class HornLocalizer
{
    private static $fallbackLanguage = 'de';
    private static $supportedLanguages = array('de', 'en', 'ru', 'jp');

    public static function i18n($key)
    {
        return self::i18nParams($key, []);
    }

    public static function i18nParams($key, $params)
    {
        $messageFormatter = self::getMessageFormatterForKeyWithLanguageFallback($key);
        if ($messageFormatter) {
            if (isset($params) && !empty($params)) {
                return $messageFormatter->format(array($params));
            }
            return $messageFormatter->format(array());
        }
        return 'Unknown key: "' . $key . '"';
    }

    /**
     * @param $key
     * @return bool|MessageFormatter false in case no key is found for the currently set language or fallback language
     */
    private static function getMessageFormatterForKeyWithLanguageFallback($key)
    {
        if (isset($GLOBALS['messages'][self::getLanguage()][trim($key)])) {
            return new MessageFormatter(self::getLanguage(), $GLOBALS['messages'][self::getLanguage()][$key]);
        }
        return false;
    }

    /**
     * Retrieve language parameter if available with fallback to en by taking care of session state as well.
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

        $lang = trim(strtolower($lang));

        if (in_array($lang, self::$supportedLanguages)) {
            return self::storeInSession($lang);
        }

        return self::storeInSession(self::$fallbackLanguage);
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

    /**
     * Store given language in session.
     */
    private static function storeInSession($language)
    {
        $_SESSION['language'] = $language;
        return $language;
    }

}
