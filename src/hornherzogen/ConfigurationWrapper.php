<?php
namespace hornherzogen;


/**
 * Class ConfigurationWrapper wraps the configuration of the application loaded from the INI file.
 * @package hornherzogen
 */
class ConfigurationWrapper
{

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

}