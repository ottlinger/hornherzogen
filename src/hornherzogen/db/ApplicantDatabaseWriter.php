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
                print "<h2>$row[week] - $row[vorname], $row[nachname], $row[email], $[city]</h2>\n";
                $results[] = self::fromDatabaseToObject($row);
            }
        }
        return $results;
    }

    private static function fromDatabaseToObject($row)
    {
        // TODO add mapping
        return new Applicant();
    }

}