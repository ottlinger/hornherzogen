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
    public static function gitrevision() {
        if(empty(ConfigurationWrapper::$gitRevision)) {
            // remove any line breaks
            ConfigurationWrapper::$gitRevision = preg_replace("#\r|\n#", "", trim(`git rev-parse --verify HEAD`));

            if(empty(ConfigurationWrapper::$gitRevision)) {
                ConfigurationWrapper::$gitRevision = 'unavailable';
            }
        }
        return ConfigurationWrapper::$gitRevision;
    }

    public static function mail()
    {
        if ($GLOBALS['horncfg']) {
            return $GLOBALS['horncfg']['mail'];
        }
    }

    public static function pdf()
    {
        if ($GLOBALS['horncfg']) {
            return $GLOBALS['horncfg']['pdf'];
        }
    }

    public static function registrationmail()
    {
        if ($GLOBALS['horncfg']) {
            return $GLOBALS['horncfg']['registrationmail'];
        }
    }

    public static function sendregistrationmails()
    {
        if ($GLOBALS['horncfg']) {
            return $GLOBALS['horncfg']['sendregistrationmails'];
        }
    }

    public static function sendinternalregistrationmails()
    {
        if ($GLOBALS['horncfg']) {
            return $GLOBALS['horncfg']['sendinternalregistrationmails'];
        }
    }

    public static function dbhost()
    {
        if ($GLOBALS['horncfg']) {
            return $GLOBALS['horncfg']['dbhost'];
        }
    }

    public static function dbuser()
    {
        if ($GLOBALS['horncfg']) {
            return $GLOBALS['horncfg']['dbuser'];
        }
    }

    public static function dbpassword()
    {
        if ($GLOBALS['horncfg']) {
            return $GLOBALS['horncfg']['dbpassword'];
        }
    }

}