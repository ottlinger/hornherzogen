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
        // retrieve former state from session and let parameter overwrite if it exists
        if (isset($_SESSION) && isset($_SESSION['language'])) {
            $sessionLanguage = trim(filter_var($_SESSION['language']));
            echo "session da:".$sessionLanguage;
        }

        if (isset($_SESSION) && isset($_SESSION['language'])) {
            $lang = trim(filter_var($_GET['lang'], FILTER_SANITIZE_STRING));
            // does not work in test mode:  $lang = trim(filter_input(INPUT_GET, "lang", FILTER_SANITIZE_STRING));
        }

        if (isset($lang) && !empty($lang)) {
            echo "param da:".$lang;
            unset($sessionLanguage);
            unset($_SESSION['language']);
        }

        if ((!isset($lang) || $lang === NULL) && isset($sessionLanguage)) {
            echo "Fallback from session:".$sessionLanguage;
            return $sessionLanguage;
        }

        if (empty($lang) && empty($sessionLanguage)) {
            echo "No Param given";
            return self::storeInSession(self::$fallbackLanguage);
        }

        $lang = strtolower($lang);
        switch ($lang) {
            case "de";
            case "en";
            case "ru";
            case "jp";
                echo "fallthroughValid:".$lang;
                return self::storeInSession($lang);
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

    public static function i18n($key)
    {
        $messageFormatter = new MessageFormatter(HornLocalizer::getLanguage(), $GLOBALS['messages'][self::getLanguage()][$key]);
        echo $messageFormatter->format(array());
    }

    public static function i18nParams($key, $params)
    {
        $messageFormatter = new MessageFormatter(HornLocalizer::getLanguage(), $GLOBALS['messages'][self::getLanguage()][$key]);
        echo $messageFormatter->format(array($params));
    }

}
