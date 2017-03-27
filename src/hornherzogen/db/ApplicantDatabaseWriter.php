<?php

namespace hornherzogen\db;

use hornherzogen\Applicant;

class ApplicantDatabaseWriter extends BaseDatabaseWriter
{
    /**
     * Check if the given applicant already exists. If so, add a random salt (=timestamp) to its fullname and persist it into the database.
     * @param $applicantInput
     * @return string last inserted database Id or 4711 in test mode.
     */
    function persist($applicantInput)
    {
        if (NULL != $this->getByNameAndMailadress($applicantInput->getFirstname(), $applicantInput->getLastname(), $applicantInput->getEmail())) {
            $applicantInput->setFullName($this->formHelper->timestamp());
        }

        $parser = new ApplicantDatabaseParser($applicantInput);

        // fake test mode
        if (!isset($this->database)) {
            return "4711";
        }

        $statement = $this->database->prepare($parser->getInsertIntoSql());
        $statement->execute($parser->getInsertIntoValues());

        return $this->database->lastInsertId();
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

        $query = 'SELECT * from `applicants` a ';
        $query .= ' WHERE a.vorname = "' . $firstname . '" ';
        $query .= ' AND a.nachname = "' . $lastname . '" ';
        $query .= ' AND a.email = "' . $mail . '" ';
        $dbResult = $this->database->query($query);
        if (false === $dbResult) {
            $error = $this->database->errorInfo();
            print "DB-Error\nSQLError=$error[0]\nDBError=$error[1]\nMessage=$error[2]";
            return NULL;
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
                // TODO add mapping to name from status.id
                $applicant->setCurrentStatus($row['statusId']);
            }

            if ($this->formHelper->isSetAndNotEmptyInArray($row, 'language')) {
                $applicant->setLanguage($row['language']);
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
            if (isset($week) && strlen($week)) {
                $query .= " WHERE a.week LIKE '%" . trim('' . $week) . "%'";
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

    function removeById($applicantId)
    {
        if ($this->isHealthy() && isset($applicantId) && strlen($applicantId)) {
            return $this->database->exec("DELETE from `applicants` WHERE id = " . $this->databaseHelper->makeSQLCapable($applicantId, $this->database));
        }
        return 0;
    }

}