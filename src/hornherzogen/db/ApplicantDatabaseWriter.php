<?php
namespace hornherzogen\db;

use hornherzogen\Applicant;

class ApplicantDatabaseWriter extends BaseDatabaseWriter
{

    function getByNameAndMailadress($firstname, $lastname, $mail)
    {
        echo $firstname;
        echo $lastname;
        echo $mail;
    }

    function persist($applicantInput)
        // add logics to create a new combined Name
        // add helper method to transform a given Object into an INSERT INTO
    {
        echo $applicantInput;
    }

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
                // TODO replace me
                print "<h3>DEBUG: w:$row[week] - v:$row[vorname], n:$row[nachname], @:$row[email], c:$row[city]</h3>\n";
                $results[] = $this->fromDatabaseToObject($row);
            }
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
        }

        return $applicant;
    }

}