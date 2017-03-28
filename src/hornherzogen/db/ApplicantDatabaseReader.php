<?php
declare(strict_types=1);

namespace hornherzogen\db;

class ApplicantDatabaseReader extends BaseDatabaseWriter
{
    // implemented by #66
    public function listByFoodCategoryPerWeek($week)
    {
        if ($this->isHealthy()) {
            $results = array();
            if (self::isHealthy()) {
                $query = "SELECT * from `applicants` a";
                // if week == null - return all, else for the given week
                if (isset($week) && strlen($week)) {
                    $query .= " WHERE a.week LIKE '%" . trim('' . $week) . "%'";
                }
                $query .= " ORDER by a.week, a.essen";

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
        return array();
    }

    public function listByRoomCategoryPerWeek($week)
    {
        // array of array
        // 1 -> all single rooms
        // 2 -> all double rooms
        // 3 -> all triple rooms
        // 4 -> all other rooms
        $results = array(
            '1' => array(),
            '2' => array(),
            '3' => array(),
            '4' => array(),
        );

        if ($this->isHealthy()) {
            if (self::isHealthy()) {
                $query = "SELECT * from `applicants` a";
                // if week == null - return all, else for the given week
                if (isset($week) && strlen($week)) {
                    $query .= " WHERE a.week LIKE '%" . trim('' . $week) . "%'";
                }
                $query .= " ORDER by a.week, a.room";

                $dbResult = $this->database->query($query);
                if (false === $dbResult) {
                    $error = $this->database->errorInfo();
                    print "DB-Error\nSQLError=$error[0]\nDBError=$error[1]\nMessage=$error[2]";
                }
                while ($row = $dbResult->fetch()) {
                    $applicant = $this->databaseHelper->fromDatabaseToObject($row);

                    switch ($applicant->getRoom()) {
                        case "1bed":
                            $results['1'][] = $applicant;
                            break;

                        case "2bed":
                            $results['2'][] = $applicant;
                            break;

                        case "3bed":
                            $results['3'][] = $applicant;
                            break;

                        default:
                            $results['4'][] = $applicant;
                            break;
                    }
                }
            }
        }

        return $results;
    }

}