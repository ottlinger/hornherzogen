<?php
namespace hornherzogen\db;

use hornherzogen\Applicant;

class ApplicantDatabaseWriter extends BaseDatabaseWriter
{

    function persist($applicantInput)
    {
        if (NULL != $this->getByNameAndMailadress($applicantInput->getFirstname(), $applicantInput->getLastname(), $applicantInput->getEmail())) {
            $applicantInput->setFullname($this->formHelper->timestamp());
        }

        // TODO handle non-optional fields and empty values
    }

    /**
     * @param $firstname
     * @param $lastname
     * @param $mail
     * @return array|null : null iff the given combination is not found in the database,
     * the possible list of found entries in an array.
     */
    function getByNameAndMailadress($firstname, $lastname, $mail)
    {
        if (!self::isHealthy()) {
            return NULL;
        }

        // TODO replace with prepared statement
        $query = 'SELECT * from `applicants` a ';
        $query .= ' WHERE a.vorname = "' . $firstname . '" ';
        $query .= ' AND a.nachname = "' . $lastname . '" ';
        $query .= ' AND a.email = "' . $mail . '" ';

        $dbResult = $this->database->query($query);
        if (false === $dbResult) {
            $error = $this->database->errorInfo();
            print "DB-Error\nSQLError=$error[0]\nDBError=$error[1]\nMessage=$error[2]";
        }

        if (0 == $dbResult->rowCount()) {
            return NULL;
        }

        $results = array();
        while ($row = $dbResult->fetch()) {
            $results[] = $this->fromDatabaseToObject($row);
        }
        return $results;
    }

    function fromDatabaseToObject($row)
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

            if ($this->formHelper->isSetAndNotEmptyInArray($row, 'combinedName')) {
                $applicant->setLastname($row['nachname']);
            }

            if ($this->formHelper->isSetAndNotEmptyInArray($row, 'street')) {
                $applicant->setLastname($row['nachname']);
            }

            if ($this->formHelper->isSetAndNotEmptyInArray($row, 'houseno')) {
                $applicant->setLastname($row['nachname']);
            }

            if ($this->formHelper->isSetAndNotEmptyInArray($row, 'plz')) {
                $applicant->setLastname($row['nachname']);
            }

            if ($this->formHelper->isSetAndNotEmptyInArray($row, 'dojo')) {
                $applicant->setLastname($row['nachname']);
            }

            if ($this->formHelper->isSetAndNotEmptyInArray($row, 'grad')) {
                $applicant->setLastname($row['nachname']);
            }

            if ($this->formHelper->isSetAndNotEmptyInArray($row, 'gradsince')) {
                $applicant->setLastname($row['nachname']);
            }

            if ($this->formHelper->isSetAndNotEmptyInArray($row, 'twano')) {
                $applicant->setLastname($row['nachname']);
            }

            if ($this->formHelper->isSetAndNotEmptyInArray($row, 'room')) {
                $applicant->setLastname($row['nachname']);
            }

            if ($this->formHelper->isSetAndNotEmptyInArray($row, 'together1')) {
                $applicant->setLastname($row['nachname']);
            }

            if ($this->formHelper->isSetAndNotEmptyInArray($row, 'together2')) {
                $applicant->setLastname($row['nachname']);
            }

            if ($this->formHelper->isSetAndNotEmptyInArray($row, 'essen')) {
                $applicant->setLastname($row['nachname']);
            }

            if ($this->formHelper->isSetAndNotEmptyInArray($row, 'flexible')) {
                $applicant->setLastname($row['nachname']);
            }

            if ($this->formHelper->isSetAndNotEmptyInArray($row, 'additionals')) {
                $applicant->setLastname($row['nachname']);
            }

            if ($this->formHelper->isSetAndNotEmptyInArray($row, 'created')) {
                $applicant->setLastname($row['nachname']);
            }

            if ($this->formHelper->isSetAndNotEmptyInArray($row, 'mailed')) {
                $applicant->setConfirmedAt($row['nachname']);
            }

            if ($this->formHelper->isSetAndNotEmptyInArray($row, 'verified')) {
                // TODO
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
        }

        return $applicant;
    }

    // add logics to create a new combined Name
    // add helper method to transform a given Object into an INSERT INTO

    function getAllByWeek($week = NULL)
    {
        $results = array();
        if (self::isHealthy()) {
            $query = "SELECT * from `applicants` a";
            // if week == null - return all, else for the given week
            if (isset($week)) {
                $query .= " WHERE a.week LIKE '%" . trim($week) . "%'";
            }

            $dbResult = $this->database->query($query);
            if (false === $dbResult) {
                $error = $this->database->errorInfo();
                print "DB-Error\nSQLError=$error[0]\nDBError=$error[1]\nMessage=$error[2]";
            }
            while ($row = $dbResult->fetch()) {
                $results[] = $this->fromDatabaseToObject($row);
            }
        }
        return $results;
    }

}