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

    public function mail()
    {
        return self::getFromHornConfiguration('mail');
    }

    public function debug()
    {
        return self::getFromHornConfiguration('debug');
    }

    public function pdf()
    {
        return self::getFromHornConfiguration('pdf');
    }

    public function registrationmail()
    {
        return self::getFromHornConfiguration('registrationmail');
    }

    public function sendregistrationmails()
    {
        return boolval(self::getFromHornConfiguration('sendregistrationmails'));
    }

    public function sendinternalregistrationmails()
    {
        return boolval(self::getFromHornConfiguration('sendinternalregistrationmails'));
    }

    public function dbhost()
    {
        return self::getFromHornConfiguration('dbhost');
    }

    public function dbuser()
    {
        return self::getFromHornConfiguration('dbuser');
    }

    public function dbname()
    {
        return self::getFromHornConfiguration('dbname');
    }

    public function dbpassword()
    {
        return self::getFromHornConfiguration('dbpassword');
    }

    /**
     * @return bool TRUE if database configuration is complete
     */

    public function isValidDatabaseConfig()
    {
        return !empty($this->dbpassword()) && !empty($this->dbuser()) && !empty($this->dbhost()) && !empty($this->dbname());
    }

}