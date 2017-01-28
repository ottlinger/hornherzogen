<?php
namespace hornherzogen;


/**
 * Class ConfigurationWrapper wraps the configuration of the application loaded from the INI file.
 * @package hornherzogen
 */
class ConfigurationWrapper
{
    private static $gitRevision = null;

    /**
     * Tries to retrieve the current git revision or sets to unavailable in case of underlying errors.
     */
    public static function gitrevision()
    {
        if (empty(ConfigurationWrapper::$gitRevision)) {
            // remove any line breaks
            ConfigurationWrapper::$gitRevision = preg_replace("#\r|\n#", "", trim(`git rev-parse --verify HEAD`));

            if (empty(ConfigurationWrapper::$gitRevision)) {
                ConfigurationWrapper::$gitRevision = 'unavailable';
            }
        }
        return ConfigurationWrapper::$gitRevision;
    }

    public static function mail()
    {
        if ($GLOBALS['horncfg'] && isset($GLOBALS['horncfg']['mail'])) {
            return trim($GLOBALS['horncfg']['mail']);
        }
    }

    public static function debug()
    {
        if ($GLOBALS['horncfg'] && isset($GLOBALS['horncfg']['debug'])) {
            return trim($GLOBALS['horncfg']['debug']);
        }
    }

    public static function pdf()
    {
        if ($GLOBALS['horncfg'] && isset($GLOBALS['horncfg']['pdf'])) {
            return trim($GLOBALS['horncfg']['pdf']);
        }
    }

    public static function registrationmail()
    {
        if ($GLOBALS['horncfg'] && isset($GLOBALS['horncfg']['registrationmail'])) {
            return trim($GLOBALS['horncfg']['registrationmail']);
        }
    }

    public static function sendregistrationmails()
    {
        if ($GLOBALS['horncfg'] && isset($GLOBALS['horncfg']['sendregistrationmails'])) {
            return trim($GLOBALS['horncfg']['sendregistrationmails']);
        }
    }

    public static function sendinternalregistrationmails()
    {
        if ($GLOBALS['horncfg'] && isset($GLOBALS['horncfg']['sendinternalregistrationmails'])) {
            return trim($GLOBALS['horncfg']['sendinternalregistrationmails']);
        }
    }

    public static function dbhost()
    {
        if ($GLOBALS['horncfg'] && isset($GLOBALS['horncfg']['dbhost'])) {
            return trim($GLOBALS['horncfg']['dbhost']);
        }
    }

    public static function dbuser()
    {
        if ($GLOBALS['horncfg'] && isset($GLOBALS['horncfg']['dbuser'])) {
            return trim($GLOBALS['horncfg']['dbuser']);
        }
    }

    public static function dbname()
    {
        if ($GLOBALS['horncfg'] && isset($GLOBALS['horncfg']['dbname'])) {
            return trim($GLOBALS['horncfg']['dbname']);
        }
    }

    public static function dbpassword()
    {
        if ($GLOBALS['horncfg'] && isset($GLOBALS['horncfg']['dbpassword'])) {
            return trim($GLOBALS['horncfg']['dbpassword']);
        }
    }

    /**
     * @return bool TRUE if database configuration is complete
     */

    public static function isValidDatabaseConfig()
    {
        return !empty(self::dbpassword()) && !empty(self::dbuser()) && !empty(self::dbhost()) && !empty(self::dbname());
    }

}