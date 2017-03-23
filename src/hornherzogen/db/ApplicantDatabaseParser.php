<?php
namespace hornherzogen\db;

use hornherzogen\HornLocalizer;

/**
 * Class ApplicantDatabaseParser encapsulates an INSERT INTO-sql String from a given applicant.
 * @package hornherzogen\db
 */
class ApplicantDatabaseParser
{
    private $values;
    private $placeholder;
    private $sql;
    private $applicant;

    function __construct($applicant)
    {
        $this->applicant = $applicant;
        $this->values = array();
        $this->placeholder = array();

        $this->prepare();
    }

    private function prepare()
    {
        $this->parseValues();
        $this->sql = "INSERT INTO applicants (" . implode(",", $this->placeholder) . ") VALUES (" . implode(",", $this->values) . ")";
    }

    private function parseValues()
    {
        if (boolval($this->emptyToNull($this->applicant->getLanguage()))) {
            $this->values[] = $this->trimAndMask($this->applicant->getLanguage());
            $this->placeholder[] = 'language';
        }

        if (boolval($this->emptyToNull($this->applicant->getWeek()))) {
            $this->values[] = $this->trimAndMask($this->applicant->getWeek());
            $this->placeholder[] = 'week';
        }

        if (boolval($this->emptyToNull($this->applicant->getGender()))) {
            $this->values[] = $this->trimAndMask($this->applicant->getGender());
            $this->placeholder[] = 'gender';
        }

        if (boolval($this->emptyToNull($this->applicant->getEmail()))) {
            $this->values[] = $this->trimAndMask($this->applicant->getEmail());
            $this->placeholder[] = 'email';
        }

        if (boolval($this->emptyToNull($this->applicant->getCity()))) {
            $this->values[] = $this->trimAndMask($this->applicant->getCity());
            $this->placeholder[] = 'city';
        }

        if (boolval($this->emptyToNull($this->applicant->getCountry()))) {
            $this->values[] = $this->trimAndMask($this->applicant->getCountry());
            $this->placeholder[] = 'country';
        }

        if (boolval($this->emptyToNull($this->applicant->getFirstname()))) {
            $this->values[] = $this->trimAndMask($this->applicant->getFirstname());
            $this->placeholder[] = 'vorname';
        }

        if (boolval($this->emptyToNull($this->applicant->getLastname()))) {
            $this->values[] = $this->trimAndMask($this->applicant->getLastname());
            $this->placeholder[] = 'nachname';
        }

        $full = $this->applicant->getFullName();
        if (boolval($this->emptyToNull($full))) {
            $this->values[] = $this->trimAndMask($full);
            $this->placeholder[] = 'combinedName';
        }

        if (boolval($this->emptyToNull($this->applicant->getStreet()))) {
            $this->values[] = $this->trimAndMask($this->applicant->getStreet());
            $this->placeholder[] = 'street';
        }

        if (boolval($this->emptyToNull($this->applicant->getHouseNumber()))) {
            $this->values[] = $this->trimAndMask($this->applicant->getHouseNumber());
            $this->placeholder[] = 'houseno';
        }

        if (boolval($this->emptyToNull($this->applicant->getZipCode()))) {
            $this->values[] = $this->trimAndMask($this->applicant->getZipCode());
            $this->placeholder[] = 'plz';
        }

        if (boolval($this->emptyToNull($this->applicant->getGrading()))) {
            $this->values[] = $this->trimAndMask($this->applicant->getGrading());
            $this->placeholder[] = 'grad';
        }

        if (boolval($this->emptyToNull($this->applicant->getDateOfLastGrading()))) {
            $this->values[] = $this->trimAndMask($this->applicant->getDateOfLastGrading());
            $this->placeholder[] = 'gradsince';
        }

        if (boolval($this->emptyToNull($this->applicant->getTwaNumber()))) {
            $this->values[] = $this->trimAndMask($this->applicant->getTwaNumber());
            $this->placeholder[] = 'twano';
        }

        if (boolval($this->emptyToNull($this->applicant->getRoom()))) {
            $this->values[] = $this->trimAndMask($this->applicant->getRoom());
            $this->placeholder[] = 'room';
        }

        if (boolval($this->emptyToNull($this->applicant->getPartnerOne()))) {
            $this->values[] = $this->trimAndMask($this->applicant->getPartnerOne());
            $this->placeholder[] = 'together1';
        }

        if (boolval($this->emptyToNull($this->applicant->getPartnerTwo()))) {
            $this->values[] = $this->trimAndMask($this->applicant->getPartnerTwo());
            $this->placeholder[] = 'together2';
        }

        if (boolval($this->emptyToNull($this->applicant->getFoodCategory()))) {
            $this->values[] = $this->trimAndMask($this->applicant->getFoodCategory());
            $this->placeholder[] = 'essen';
        }

        if (boolval($this->emptyToNull($this->applicant->getFlexible()))) {
            $this->values[] = $this->trimAndMask($this->applicant->getFlexible());
            $this->placeholder[] = 'flexible';
        }

        if (boolval($this->emptyToNull($this->applicant->getRemarks()))) {
            $this->values[] = $this->trimAndMask($this->applicant->getRemarks());
            $this->placeholder[] = 'additionals';
        }

        if (boolval($this->emptyToNull($this->applicant->getCurrentStatus()))) {
            $this->values[] = $this->emptyToNull($this->applicant->getCurrentStatus());
            $this->placeholder[] = 'statusId';
        }

        // we silently ignore all status fields here since parser is *Yet* only used for initial save to the database
        /*
             $applicant->setCreatedAt($row['created']);
             $applicant->setMailedAt($row['mailed']);
             $applicant->setConfirmedAt($row['verified']);
             $applicant->setPaymentRequestedAt($row['paymentmailed']);
             $applicant->setPaymentReceivedAt($row['paymentreceived']);
             $applicant->setBookedAt($row['booked']);
             $applicant->setCancelledAt($row['cancelled']);
        */
    }

    /**
     * Trims given data and surrounds with quotes for SQL insertion.
     * @param $input
     * @return null|string
     */
    // TODO extract to DatabaseHelper
    public function trimAndMask($input)
    {
        $trimmed = $this->emptyToNull($input);
        if (boolval($trimmed)) {
            return '\'' . $trimmed . '\'';
        }
        return NULL;
    }

    // TODO extract to DatabaseHelper
    public function emptyToNull($input)
    {
        if (isset($input) && strlen(trim($input))) {
            return trim($input);
        }
        return NULL;
    }

    public function getInsertIntoSql()
    {
        return $this->sql;
    }

    public function getInsertIntoValues()
    {
        return $this->values;
    }

}