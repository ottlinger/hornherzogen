<?php
declare(strict_types=1);

namespace hornherzogen\db;

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
        $this->databaseHelper->logDatabaseErrors($dbResult, $this->database);

        if (0 == $dbResult->rowCount()) {
            return NULL;
        }

        $results = array();
        while ($row = $dbResult->fetch()) {
            $results[] = $this->fromDatabaseToObject($row);
        }

        return $results;
    }

    function removeById($applicantId)
    {
        if ($this->isHealthy() && isset($applicantId) && strlen($applicantId)) {
            // remove any existing room bookings
            $removedBookings = $this->removeExistingRoomBookings($applicantId);
            $result = $this->database->exec("DELETE from `applicants` WHERE id = " . $this->databaseHelper->makeSQLCapable($applicantId, $this->database));
            $this->databaseHelper->logDatabaseErrors($result, $this->database);

            if (isset($removedBookings) && is_numeric($removedBookings) && $removedBookings > 0) {
                $result . " mit " . $removedBookings . " Raumbuchungen";
            }

            return $result;
        }
        return (0 . " ohne Raumbuchungen");
    }

    function removeExistingRoomBookings($applicantId)
    {
        if ($this->isHealthy() && isset($applicantId) && is_numeric($applicantId)) {
            $result = $this->database->exec("DELETE FROM `roombooking` WHERE applicantId=" . $this->databaseHelper->makeSQLCapable($applicantId, $this->database));
            $this->databaseHelper->logDatabaseErrors($result, $this->database);
        }
        return NULL;
    }

}