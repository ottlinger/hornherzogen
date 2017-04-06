<?php
declare(strict_types=1);

namespace hornherzogen;

use MessageFormatter;

class HornLocalizer
{
    public static $fallbackLanguage = 'de';
    public static $supportedLanguages = array('de', 'en', 'ru', 'jp');

    public function i18n($key)
    {
        return $this->i18nParams($key, []);
    }

    public function i18nParams($key, $params)
    {
        $messageFormatter = $this->getMessageFormatterForKeyWithLanguageFallback($key);
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
    private function getMessageFormatterForKeyWithLanguageFallback($key)
    {
        if (isset($GLOBALS['messages'][$this->getLanguage()][trim('' . $key)])) {
            return new MessageFormatter($this->getLanguage(), $GLOBALS['messages'][$this->getLanguage()][$key]);
        }

        if (isset($GLOBALS['messages'][self::$fallbackLanguage][trim('' . $key)])) {
            return new MessageFormatter(self::$fallbackLanguage, $GLOBALS['messages'][self::$fallbackLanguage][$key]);
        }

        return false;
    }

    /**
     * Retrieve language parameter if available with fallback to en by taking care of session state as well.
     * @return string
     */
    public function getLanguage()
    {
        $sessionLanguage = self::getLanguageFromSession();
        $lang = $this->getLanguageFromUrlParameter();

        // no URL parameter given
        if (empty($lang) && empty($sessionLanguage)) {
            return $this->storeInSession(self::$fallbackLanguage);
        }

        // fallback from session
        if ((!isset($lang) || $lang === NULL) && isset($sessionLanguage)) {
            $lang = $sessionLanguage;
        }

        $lang = trim('' . strtolower($lang));

        if (in_array($lang, self::$supportedLanguages)) {
            return $this->storeInSession($lang);
        }

        return $this->storeInSession(self::$fallbackLanguage);
    }

    /**
     * @return null|string language setting from session store.
     */
    static function getLanguageFromSession()
    {
        return (isset($_SESSION) && isset($_SESSION['language'])) ? trim('' . filter_var($_SESSION['language'], FILTER_SANITIZE_STRING)) : NULL;
    }

    /**
     * @return null|string language setting from session store.
     */
    static function getLanguageFromUrlParameter()
    {
        // HINT: not working in tests:
        //  return (isset($_GET) && isset($_GET['lang'])) ? trim('' . filter_input(INPUT_GET, "lang", FILTER_SANITIZE_STRING)) : NULL;
        return (isset($_GET) && isset($_GET['lang'])) ? trim('' . filter_var($_GET['lang'], FILTER_SANITIZE_STRING)) : NULL;
    }

    /**
     * Store given language in session and return the saved language.
     * @param $language current language string/ISO-2
     * @return mixed given language stored in session.
     */
    private function storeInSession($language)
    {
        $_SESSION['language'] = $language;
        return $language;
    }

}
