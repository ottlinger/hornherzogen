<?php
namespace hornherzogen;

/**
 * Class ApplicantInput
 * holds any validation errors when processing user data extracted from $_POST[].
 * @package hornherzogen
 */
final class ApplicantInput extends Applicant
{
    private $errors = array();
    private $success = array();

    /**
     * @var int total elements that can be provided in the web form.
     */
    private $total = 17;

    public function hasErrors()
    {
        return !($this->total == sizeof($this->success) && 0 == sizeof($this->errors));
    }

    public function showHasError($field)
    {
        if (in_array($field, $this->errors)) {
            return " has-error has-feedback";
        }
    }

    public function showIsOkay($field)
    {
        if (in_array($field, $this->success)) {
            return " has-success";
        }
    }

    public static function isValidEmail($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return sprintf(
                    '"%s" is not a valid email address',
                    $email
                );
        }
        return true;
    }

    public function addError($field) {
        array_push($this->errors, $field);
    }

    public function addSuccess($field) {
        array_push($this->success, $field);
    }

    /**
     * Extracts data from $_POST[].
     */
    public function parse()
    {

    }

}