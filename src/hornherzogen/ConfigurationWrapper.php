<?php
namespace hornherzogen;


/**
 * Class ConfigurationWrapper wraps the configuration of the application loaded from the INI file.
 * @package hornherzogen
 */
class ConfigurationWrapper
{
    const LINEBREAK = "<br />\r\n";

    public function __toString()
    {
        $status = "<pre>Current configuration is: " . self::LINEBREAK;
        $status .= "DB-User: " . self::maskWithAsterisk($this->dbuser(), 3) . self::LINEBREAK;
        $status .= "</pre>";

        return $status;
    }

    static function maskWithAsterisk($input, $fromPosition = 0)
    {
        if (isset($input) && strlen($input) > 0) {
            $result = "";
            $characters = str_split($input);
            for ($i = 0; $i < sizeof($characters); $i++) {

                if ($i >= $fromPosition) {
                    $result .= '*';
                } else {
                    $result .= $characters[$i];
                }
            }
            return $result;
        }
        return $input;
    }

    public function dbuser()
    {
        return self::getFromHornConfiguration('dbuser');
    }

    private static function getFromHornConfiguration($key)
    {
        if ($GLOBALS['horncfg'] && isset($GLOBALS['horncfg'][$key])) {
            return trim($GLOBALS['horncfg'][$key]);
        }
        return NULL;
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

    /**
     * @return bool TRUE if database configuration is complete
     */

    public function isValidDatabaseConfig()
    {
        return !empty($this->dbpassword()) && !empty($this->dbuser()) && !empty($this->dbhost()) && !empty($this->dbname());
    }

    public function dbpassword()
    {
        return self::getFromHornConfiguration('dbpassword');
    }

    public function dbhost()
    {
        return self::getFromHornConfiguration('dbhost');
    }

    public function dbname()
    {
        return self::getFromHornConfiguration('dbname');
    }

}