<?php
declare(strict_types=1);

namespace hornherzogen;

/**
 * Class ConfigurationWrapper wraps the configuration of the application loaded from the INI file.
 * @package hornherzogen
 */
class ConfigurationWrapper
{
    const LINEBREAK = "<br/>";
    private $formHelper;

    public function __construct()
    {
        $this->formHelper = new FormHelper();
    }

    public function __toString()
    {
        $status = "<pre>Current configuration is: " . self::LINEBREAK;
        $status .= "Debug mode: " . (boolval($this->debug()) ? "ON" : "OFF") . self::LINEBREAK;
        $status .= "Send mails: " . (boolval($this->sendregistrationmails()) ? "ON" : "OFF") . self::LINEBREAK;
        $status .= "Send mails internally: " . (boolval($this->sendinternalregistrationmails()) ? "ON" : "OFF") . self::LINEBREAK;
        $status .= "Contact Email: " . self::maskWithAsterisk($this->formHelper->filterUserInput($this->mail()), 7) . self::LINEBREAK;
        $status .= "Registration Email: " . self::maskWithAsterisk($this->formHelper->filterUserInput($this->registrationmail()), 10) . self::LINEBREAK;
        $status .= "DB-Host: " . self::maskWithAsterisk($this->formHelper->filterUserInput($this->dbhost()), 3) . self::LINEBREAK;
        $status .= "DB-Name: " . self::maskWithAsterisk($this->formHelper->filterUserInput($this->dbname()), 3) . self::LINEBREAK;
        $status .= "DB-User: " . self::maskWithAsterisk($this->formHelper->filterUserInput($this->dbuser()), 3) . self::LINEBREAK;
        $status .= "DB-PW: " . self::maskWithAsterisk($this->formHelper->filterUserInput($this->dbpassword()), 1) . self::LINEBREAK;
        $status .= "Submission End: " . $this->formHelper->filterUserInput($this->submissionend()) . self::LINEBREAK;
        $status .= "Application PDF: " . $this->formHelper->filterUserInput($this->pdf()) . self::LINEBREAK;
        $status .= "Superuser list: " . $this->formHelper->filterUserInput($this->superuser()) . self::LINEBREAK;
        $status .= "</pre>";

        return $status;
    }

    public function debug()
    {
        return self::getFromHornConfiguration('debug');
    }

    private static function getFromHornConfiguration($key)
    {
        if ($GLOBALS['horncfg'] && isset($GLOBALS['horncfg'][$key])) {
            return trim('' . $GLOBALS['horncfg'][$key]);
        }
        return NULL;
    }

    public function sendregistrationmails()
    {
        return boolval(self::getFromHornConfiguration('sendregistrationmails'));
    }

    public function sendinternalregistrationmails()
    {
        return boolval(self::getFromHornConfiguration('sendinternalregistrationmails'));
    }

    static function maskWithAsterisk($input, $fromPosition = 0)
    {
        if (isset($input) && strlen($input) > 0) {
            $result = "";
            $characters = str_split($input);
            for ($i = 0; $i < sizeof($characters); $i++) {

                if ($i >= $fromPosition) {
                    $result .= '*';
                    continue;
                }
                $result .= $characters[$i];
            }
            return $result;
        }
        return $input;
    }

    public function mail()
    {
        return self::getFromHornConfiguration('mail');
    }

    public function registrationmail()
    {
        return self::getFromHornConfiguration('registrationmail');
    }

    public function dbhost()
    {
        return self::getFromHornConfiguration('dbhost');
    }

    public function dbname()
    {
        return self::getFromHornConfiguration('dbname');
    }

    public function dbuser()
    {
        return self::getFromHornConfiguration('dbuser');
    }

    public function dbpassword()
    {
        return self::getFromHornConfiguration('dbpassword');
    }

    public function submissionend()
    {
        return self::getFromHornConfiguration('submissionend');
    }

    public function pdf()
    {
        return self::getFromHornConfiguration('pdf');
    }

    // CSV only!
    public function superuser()
    {
        return self::getFromHornConfiguration('superuser');
    }

    /**
     * @return bool TRUE if database configuration is complete
     */

    public function isValidDatabaseConfig()
    {
        return !empty($this->dbpassword()) && !empty($this->dbuser()) && !empty($this->dbhost()) && !empty($this->dbname());
    }

}