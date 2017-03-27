<?php
declare(strict_types=1);

namespace hornherzogen\db;

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
    private $databaseHelper;

    function __construct($applicant)
    {
        $this->databaseHelper = new DatabaseHelper();
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
        if (boolval($this->databaseHelper->emptyToNull($this->applicant->getLanguage()))) {
            $this->values[] = $this->databaseHelper->trimAndMask($this->applicant->getLanguage());
            $this->placeholder[] = 'language';
        }

        if (boolval($this->databaseHelper->emptyToNull($this->applicant->getWeek()))) {
            $this->values[] = $this->databaseHelper->trimAndMask($this->applicant->getWeek());
            $this->placeholder[] = 'week';
        }

        if (boolval($this->databaseHelper->emptyToNull($this->applicant->getGender()))) {
            $this->values[] = $this->databaseHelper->trimAndMask($this->applicant->getGender());
            $this->placeholder[] = 'gender';
        }

        if (boolval($this->databaseHelper->emptyToNull($this->applicant->getEmail()))) {
            $this->values[] = $this->databaseHelper->trimAndMask($this->applicant->getEmail());
            $this->placeholder[] = 'email';
        }

        if (boolval($this->databaseHelper->emptyToNull($this->applicant->getCity()))) {
            $this->values[] = $this->databaseHelper->trimAndMask($this->applicant->getCity());
            $this->placeholder[] = 'city';
        }

        if (boolval($this->databaseHelper->emptyToNull($this->applicant->getCountry()))) {
            $this->values[] = $this->databaseHelper->trimAndMask($this->applicant->getCountry());
            $this->placeholder[] = 'country';
        }

        if (boolval($this->databaseHelper->emptyToNull($this->applicant->getFirstname()))) {
            $this->values[] = $this->databaseHelper->trimAndMask($this->applicant->getFirstname());
            $this->placeholder[] = 'vorname';
        }

        if (boolval($this->databaseHelper->emptyToNull($this->applicant->getLastname()))) {
            $this->values[] = $this->databaseHelper->trimAndMask($this->applicant->getLastname());
            $this->placeholder[] = 'nachname';
        }

        $full = $this->applicant->getFullName();
        if (boolval($this->databaseHelper->emptyToNull($full))) {
            $this->values[] = $this->databaseHelper->trimAndMask($full);
            $this->placeholder[] = 'combinedName';
        }

        if (boolval($this->databaseHelper->emptyToNull($this->applicant->getStreet()))) {
            $this->values[] = $this->databaseHelper->trimAndMask($this->applicant->getStreet());
            $this->placeholder[] = 'street';
        }

        if (boolval($this->databaseHelper->emptyToNull($this->applicant->getHouseNumber()))) {
            $this->values[] = $this->databaseHelper->trimAndMask($this->applicant->getHouseNumber());
            $this->placeholder[] = 'houseno';
        }

        if (boolval($this->databaseHelper->emptyToNull($this->applicant->getZipCode()))) {
            $this->values[] = $this->databaseHelper->trimAndMask($this->applicant->getZipCode());
            $this->placeholder[] = 'plz';
        }

        if (boolval($this->databaseHelper->emptyToNull($this->applicant->getGrading()))) {
            $this->values[] = $this->databaseHelper->trimAndMask($this->applicant->getGrading());
            $this->placeholder[] = 'grad';
        }

        if (boolval($this->databaseHelper->emptyToNull($this->applicant->getDateOfLastGrading()))) {
            $this->values[] = $this->databaseHelper->trimAndMask($this->applicant->getDateOfLastGrading());
            $this->placeholder[] = 'gradsince';
        }

        if (boolval($this->databaseHelper->emptyToNull($this->applicant->getTwaNumber()))) {
            $this->values[] = $this->databaseHelper->trimAndMask($this->applicant->getTwaNumber());
            $this->placeholder[] = 'twano';
        }

        if (boolval($this->databaseHelper->emptyToNull($this->applicant->getRoom()))) {
            $this->values[] = $this->databaseHelper->trimAndMask($this->applicant->getRoom());
            $this->placeholder[] = 'room';
        }

        if (boolval($this->databaseHelper->emptyToNull($this->applicant->getPartnerOne()))) {
            $this->values[] = $this->databaseHelper->trimAndMask($this->applicant->getPartnerOne());
            $this->placeholder[] = 'together1';
        }

        if (boolval($this->databaseHelper->emptyToNull($this->applicant->getPartnerTwo()))) {
            $this->values[] = $this->databaseHelper->trimAndMask($this->applicant->getPartnerTwo());
            $this->placeholder[] = 'together2';
        }

        if (boolval($this->databaseHelper->emptyToNull($this->applicant->getFoodCategory()))) {
            $this->values[] = $this->databaseHelper->trimAndMask($this->applicant->getFoodCategory());
            $this->placeholder[] = 'essen';
        }

        if (boolval($this->databaseHelper->emptyToNull($this->applicant->getFlexible()))) {
            $this->values[] = $this->databaseHelper->trimAndMask($this->applicant->getFlexible());
            $this->placeholder[] = 'flexible';
        }

        if (boolval($this->databaseHelper->emptyToNull($this->applicant->getRemarks()))) {
            $this->values[] = $this->databaseHelper->trimAndMask($this->applicant->getRemarks());
            $this->placeholder[] = 'additionals';
        }

        if (boolval($this->databaseHelper->emptyToNull($this->applicant->getCurrentStatus()))) {
            $this->values[] = $this->databaseHelper->emptyToNull($this->applicant->getCurrentStatus());
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

    public function getInsertIntoSql()
    {
        return $this->sql;
    }

    public function getInsertIntoValues()
    {
        return $this->values;
    }

}