<?php

declare(strict_types=1);

namespace hornherzogen;

class FormHelper
{
    private $localizer;

    public function __construct()
    {
        $this->localizer = new HornLocalizer();
    }

    /**
     * Trims the given String after the given length while keeping it in UTF-8 encoding.
     *
     * @param $input
     * @param $length
     *
     * @return string
     */
    public function trimAndCutAfter($input, $length)
    {
        if (isset($input) && isset($length)) {
            return mb_substr(trim(''.$input), 0, $length, 'UTF-8');
        }
    }

    /**
     * Returns true if the given email is a valid one, false otherwise.
     *
     * @param $email
     *
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
     *
     * @param $key
     *
     * @return bool
     */
    public function isSetAndNotEmpty($key)
    {
        return self::isSetAndNotEmptyInArray($_POST, $key);
    }

    public function isSetAndNotEmptyInArray($array, $key)
    {
        // array_key_exists($key, $array) is similar but ot null-safe
        if (isset($array) && isset($key)) {
            if (isset($array[$key])) {
                return !empty($array[$key]);
            }
        }

        return false;
    }

    public function extractMetadataForFormSubmission()
    {
        $result = [];

        if (isset($_SERVER)) {
            if (self::isSetAndNotEmptyInArray($_SERVER, 'HTTP_USER_AGENT')) {
                $result['BROWSER'] = self::filterUserInput($_SERVER['HTTP_USER_AGENT']);
            }
            if (self::isSetAndNotEmptyInArray($_SERVER, 'REMOTE_HOST')) {
                $result['R_HOST'] = self::filterUserInput($_SERVER['REMOTE_HOST']);
            }
            if (self::isSetAndNotEmptyInArray($_SERVER, 'REMOTE_ADDR')) {
                $result['R_ADDR'] = self::filterUserInput($_SERVER['REMOTE_ADDR']);
            }
        }

        $result['LANG'] = $this->localizer->getLanguage();

        return $result;
    }

    /**
     * Strips the given user input and replaces any XSS-attacks.
     *
     * @param $data
     *
     * @return string
     */
    public function filterUserInput($data)
    {
        if (isset($data)) {
            $data = trim(''.$data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            // manual hack to replace quotes here in order to make stuff more DB/MySQL compliant
            $data = strtr($data, ["'" => "\'"]);
        }

        return $data;
    }

    public function isSubmissionClosed($config)
    {
        if (isset($config)) {
            $today = strtotime($this->timestamp());
            $expiration_date = strtotime($config->submissionend());

            return boolval($expiration_date < $today);
        }

        return false;
    }

    /**
     * Yields the current timestamp to be shown in the UI.
     *
     * @return false|string
     */
    public function timestamp()
    {
        return date('Y-m-d H:i:s');
    }

    /**
     * Properly format a mailto-Link for the given parameters with CC: configuration registrationmail.
     *
     * @param $toRecipient
     * @param $ccRecipient
     * @param $subject
     * @param $body
     *
     * @return string
     */
    public function convertToValidMailto($toRecipient, $ccRecipient, $subject, $body)
    {
        $result = 'mailto:'.rawurlencode(''.$toRecipient);
        $result .= '?cc='.rawurlencode(''.$ccRecipient);
        $result .= '&subject='.rawurlencode(''.$subject);
        $result .= '&body='.rawurlencode(''.$body);

        return htmlspecialchars($result);
    }
}
