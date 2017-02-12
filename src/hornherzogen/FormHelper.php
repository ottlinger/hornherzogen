<?php
namespace hornherzogen;

class FormHelper
{
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
        if (isset($_POST) && isset($key)) {
            if (isset($_POST[$key])) {
                return !empty($_POST[$key]);
            }
        }
        return false;
    }

}