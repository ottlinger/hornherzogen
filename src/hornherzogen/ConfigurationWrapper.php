<?php
namespace hornherzogen;


/**
 * Class ConfigurationWrapper wraps the configuration of the application loaded from the INI file.
 * @package hornherzogen
 */
class ConfigurationWrapper
{
    private static function getFromHornConfiguration($key)
    {
        if ($GLOBALS['horncfg'] && isset($GLOBALS['horncfg'][$key])) {
            return trim($GLOBALS['horncfg'][$key]);
        }
    }

    public static function mail()
    {
        return self::getFromHornConfiguration('mail');
    }

    public static function debug()
    {
        return self::getFromHornConfiguration('debug');
    }

    public static function pdf()
    {
        return self::getFromHornConfiguration('pdf');
    }

    public static function registrationmail()
    {
        return self::getFromHornConfiguration('registrationmail');
    }

    public static function sendregistrationmails()
    {
        return boolval(self::getFromHornConfiguration('sendregistrationmails'));
    }

    public static function sendinternalregistrationmails()
    {
        return boolval(self::getFromHornConfiguration('sendinternalregistrationmails'));
    }

    public static function dbhost()
    {
        return self::getFromHornConfiguration('dbhost');
    }

    public static function dbuser()
    {
        return self::getFromHornConfiguration('dbuser');
    }

    public static function dbname()
    {
        return self::getFromHornConfiguration('dbname');
    }

    public static function dbpassword()
    {
        return self::getFromHornConfiguration('dbpassword');
    }

    /**
     * @return bool TRUE if database configuration is complete
     */

    public static function isValidDatabaseConfig()
    {
        return !empty(self::dbpassword()) && !empty(self::dbuser()) && !empty(self::dbhost()) && !empty(self::dbname());
    }

}