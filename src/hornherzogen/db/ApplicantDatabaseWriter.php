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

    // add logics to create a new combined Name
    // add helper method to transform a given Object into an INSERT INTO
    // TODO extract to reader?!
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
                $results[] = $this->databaseHelper->fromDatabaseToObject($row);
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