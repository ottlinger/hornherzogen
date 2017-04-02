<?php
declare(strict_types=1);

namespace hornherzogen\db;

class RoomDatabaseWriter extends BaseDatabaseWriter
{
    /**
     * Retrieve all applicants per week, sort the resulting list by week and food category.
     *
     * @param $week week choice, null for both weeks.
     * @return array a simple list of applicants to show in the UI
     */
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
                    //  access all members print "<h2>'$row[name]' has place for $row[capacity] people</h2>\n";
                    $results[] = $row();
                }
            }
            return $results;
        }
        return array();
    }

    /**
     * Get a list of applicants per week and sort them into an array by room category:
     * array of array
     * 1 -> all single rooms
     * 2 -> all double rooms
     * 3 -> all triple rooms
     * 4 -> all other rooms
     * @param $week week choice, null for both weeks.
     * @return array a simple list of applicants to show in the UI
     */
    public function listByRoomCategoryPerWeek($week)
    {
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

    public function performBooking($roomId, $applicantId)
    {
        if (isset($roomId) && isset($applicantId) && is_numeric($roomId) && is_numeric($applicantId)) {
            $result = $this->database->exec("INSERT INTO `roombooking` (roomId, applicantId) VALUES (" . $this->databaseHelper->trimAndMask($roomId) . "," . $this->databaseHelper->trimAndMask($applicantId) . ")");
            if (false === $result) {
                $error = $this->database->errorInfo();
                print "DB-Error\nSQLError=$error[0]\nDBError=$error[1]\nMessage=$error[2]";
            }
            return $this->database->lastInsertId();
        }
        return NULL;
    }

    public function deleteForApplicantId($applicantId)
    {
        if (isset($applicantId) && is_numeric($applicantId)) {
            $result = $this->database->exec("DELETE FROM `roombooking` WHERE applicantId=" . $this->databaseHelper->trimAndMask($applicantId));
            if (false === $result) {
                $error = $this->database->errorInfo();
                print "DB-Error\nSQLError=$error[0]\nDBError=$error[1]\nMessage=$error[2]";
            }
        }
        return NULL;
    }

}
