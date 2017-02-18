<?php
namespace hornherzogen;

class FormHelper
{
    /**
     * Trims the given String after the given length while keeping it in UTF-8 encoding.
     * @param $input
     * @param $length
     * @return string
     */
    public function trimAndCutAfter($input, $length)
    {
        if (isset($input) && isset($length)) {
            return mb_substr(trim($input), 0, $length, 'UTF-8');
        }
    }

    /**
     * Yields the current timestamp to be shown in the UI.
     * @return false|string
     */
    public function timestamp()
    {
        return date('Y-m-d H:i:s');
    }

    /**
     * Returns true if the given email is a valid one, false otherwise.
     * @param $email
     * @return bool
     */
    public function isValidEmail($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return true;
    }

    /**
     * Returns true if the given key is found in $_POST, false otherwise.
     * @param $key
     * @return bool
     */
    public function isSetAndNotEmpty($key)
    {
        return self::isSetAndNotEmptyInArray($_POST, $key);
    }

    private function isSetAndNotEmptyInArray($array, $key)
    {
        if (isset($array) && isset($key)) {
            if (isset($array[$key])) {
                return !empty($array[$key]);
            }
        }
        return false;
    }

    public function whoSendIt()
    {
        $result = array();

        if (isset($SERVER)) {
            if (self::isSetAndNotEmptyInArray($_SERVER, "HTTP_USER_AGENT")) {
                $result[] = array('BROWSER', self::filterUserInput($_SERVER["HTTP_USER_AGENT"]));
            }
            if (self::isSetAndNotEmptyInArray($_SERVER, "REMOTE_HOST")) {
                $result[] = array('R_HOST', self::filterUserInput($_SERVER["REMOTE_HOST"]));
            }
            if (self::isSetAndNotEmptyInArray($_SERVER, "REMOTE_ADDR")) {
                $result[] = array('R_ADDR', self::filterUserInput($_SERVER["REMOTE_ADDR"]));
            }
        }

        return $result;
    }

    /**
     * Strips the given user input and replaces any XSS-attacks.
     * @param $data
     * @return string
     */
    public function filterUserInput($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

}