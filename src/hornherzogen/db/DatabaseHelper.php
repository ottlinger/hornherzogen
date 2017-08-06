<?php

declare(strict_types=1);

namespace hornherzogen\db;

use hornherzogen\Applicant;
use hornherzogen\FormHelper;

class DatabaseHelper
{
    private $formHelper;

    public function __construct()
    {
        $this->formHelper = new FormHelper();
    }

    /**
     * Trims given data and surrounds with quotes for SQL insertion after removing any XSS stuff.
     *
     * @param $input
     *
     * @return null|string
     */
    public function trimAndMask($input)
    {
        $trimmed = $this->emptyToNull($input);
        if (boolval($trimmed)) {
            return '\''.$this->formHelper->filterUserInput($trimmed).'\'';
        }
    }

    /**
     * A given empty String is converted to a NULL value.
     *
     * @param $input
     *
     * @return null|string
     */
    public function emptyToNull($input)
    {
        if (isset($input) && strlen(trim(''.$input))) {
            return trim(''.$input);
        }
    }

    /**
     * Replaces the given String to make it compliant with a SQL-statement, thus all percentages and underscores are properly escaped and the String is quoted.
     *
     * @param $input input String to handle
     * @param $database optional given PDO/database connection that can be used to properly quote it, if not provided simple quotes will be added in the beginning and at the end.
     *
     * @return string
     */
    public function makeSQLCapable($input, $database)
    {
        if (isset($input)) {
            $mask = isset($database) ? $database->quote($input) : "'".$input."'";
            $mask = strtr($mask, ['_' => '\_', '%' => '\%']);

            return $mask;
        }

        return $input;
    }

    public function fromDatabaseToObject($row)
    {
        $applicant = new Applicant();
        if (isset($row)) {
            if ($this->formHelper->isSetAndNotEmptyInArray($row, 'id')) {
                $applicant->setPersistenceId($row['id']);
            }

            if ($this->formHelper->isSetAndNotEmptyInArray($row, 'week')) {
                $applicant->setWeek($row['week']);
            }

            if ($this->formHelper->isSetAndNotEmptyInArray($row, 'gender')) {
                $applicant->setGender($row['gender']);
            }

            if ($this->formHelper->isSetAndNotEmptyInArray($row, 'email')) {
                $applicant->setEmail($row['email']);
            }

            if ($this->formHelper->isSetAndNotEmptyInArray($row, 'city')) {
                $applicant->setCity($row['city']);
            }

            if ($this->formHelper->isSetAndNotEmptyInArray($row, 'country')) {
                $applicant->setCountry($row['country']);
            }

            if ($this->formHelper->isSetAndNotEmptyInArray($row, 'vorname')) {
                $applicant->setFirstname($row['vorname']);
            }

            if ($this->formHelper->isSetAndNotEmptyInArray($row, 'nachname')) {
                $applicant->setLastname($row['nachname']);
            }

            if ($this->formHelper->isSetAndNotEmptyInArray($row, 'street')) {
                $applicant->setStreet($row['street']);
            }

            if ($this->formHelper->isSetAndNotEmptyInArray($row, 'houseno')) {
                $applicant->setHouseNumber($row['houseno']);
            }

            if ($this->formHelper->isSetAndNotEmptyInArray($row, 'plz')) {
                $applicant->setZipCode($row['plz']);
            }

            if ($this->formHelper->isSetAndNotEmptyInArray($row, 'dojo')) {
                $applicant->setDojo($row['dojo']);
            }

            if ($this->formHelper->isSetAndNotEmptyInArray($row, 'grad')) {
                $applicant->setGrading($row['grad']);
            }

            if ($this->formHelper->isSetAndNotEmptyInArray($row, 'gradsince')) {
                $applicant->setDateOfLastGrading($row['gradsince']);
            }

            if ($this->formHelper->isSetAndNotEmptyInArray($row, 'twano')) {
                $applicant->setTwaNumber($row['twano']);
            }

            if ($this->formHelper->isSetAndNotEmptyInArray($row, 'room')) {
                $applicant->setRoom($row['room']);
            }

            if ($this->formHelper->isSetAndNotEmptyInArray($row, 'together1')) {
                $applicant->setPartnerOne($row['together1']);
            }

            if ($this->formHelper->isSetAndNotEmptyInArray($row, 'together2')) {
                $applicant->setPartnerTwo($row['together2']);
            }

            if ($this->formHelper->isSetAndNotEmptyInArray($row, 'essen')) {
                $applicant->setFoodCategory($row['essen']);
            }

            if ($this->formHelper->isSetAndNotEmptyInArray($row, 'flexible')) {
                $applicant->setFlexible($row['flexible']);
            }

            if ($this->formHelper->isSetAndNotEmptyInArray($row, 'additionals')) {
                $applicant->setRemarks($row['additionals']);
            }

            if ($this->formHelper->isSetAndNotEmptyInArray($row, 'created')) {
                $applicant->setCreatedAt($row['created']);
            }

            if ($this->formHelper->isSetAndNotEmptyInArray($row, 'mailed')) {
                $applicant->setMailedAt($row['mailed']);
            }

            if ($this->formHelper->isSetAndNotEmptyInArray($row, 'verified')) {
                $applicant->setConfirmedAt($row['verified']);
            }

            if ($this->formHelper->isSetAndNotEmptyInArray($row, 'paymentmailed')) {
                $applicant->setPaymentRequestedAt($row['paymentmailed']);
            }

            if ($this->formHelper->isSetAndNotEmptyInArray($row, 'paymentreceived')) {
                $applicant->setPaymentReceivedAt($row['paymentreceived']);
            }

            if ($this->formHelper->isSetAndNotEmptyInArray($row, 'booked')) {
                $applicant->setBookedAt($row['booked']);
            }

            if ($this->formHelper->isSetAndNotEmptyInArray($row, 'cancelled')) {
                $applicant->setCancelledAt($row['cancelled']);
            }

            if ($this->formHelper->isSetAndNotEmptyInArray($row, 'statusId')) {
                $applicant->setCurrentStatus($row['statusId']);
            }

            if ($this->formHelper->isSetAndNotEmptyInArray($row, 'language')) {
                $applicant->setLanguage($row['language']);
            }
        }

        return $applicant;
    }

    /**
     * Logs error information in case of database/SQL errors.
     *
     * @param $result PDO-database result.
     * @param $database Database connection the errors happen on/are extracted from.
     */
    public function logDatabaseErrors($result, $database)
    {
        if (isset($result) && isset($database) && false === $result) {
            $error = $database->errorInfo();
            echo "DB-Error\nSQLError=$error[0]\nDBError=$error[1]\nMessage=$error[2]";
        }
    }
}
