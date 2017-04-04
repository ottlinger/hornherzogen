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
        $results = array();
        if ($this->isHealthy()) {
            $dbResult = $this->database->query($this->buildQuery($week));

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
            $dbResult = $this->database->query($this->buildQuery($week));
            
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

        return $results;
    }

    /**
     * Book the given roomId for the applicantId.
     * @param $roomId database roomId
     * @param $applicantId database applicantId
     * @return null or the id of the inserted booking.
     */
    public function performBooking($roomId, $applicantId)
    {
        if ($this->isHealthy() && isset($roomId) && isset($applicantId) && is_numeric($roomId) && is_numeric($applicantId)) {
            $result = $this->database->exec("INSERT INTO `roombooking` (roomId, applicantId) VALUES (" . $this->databaseHelper->trimAndMask($roomId) . "," . $this->databaseHelper->trimAndMask($applicantId) . ")");
            if (false === $result) {
                $error = $this->database->errorInfo();
                print "DB-Error\nSQLError=$error[0]\nDBError=$error[1]\nMessage=$error[2]";
            }
            return $this->database->lastInsertId();
        }
        return NULL;
    }

    /**
     * Remove *all* room bookings for the given applicantId.
     * @param $applicantId databaseId of the applicant.
     * @return null or the number of affected rows.
     */
    public function deleteForApplicantId($applicantId)
    {
        if ($this->isHealthy() && isset($applicantId) && is_numeric($applicantId)) {
            $result = $this->database->exec("DELETE FROM `roombooking` WHERE applicantId=" . $this->databaseHelper->trimAndMask($applicantId));
            if (false === $result) {
                $error = $this->database->errorInfo();
                print "DB-Error\nSQLError=$error[0]\nDBError=$error[1]\nMessage=$error[2]";
            }
        }
        return NULL;
    }

    /**
     * Retrieve if the given roomId can be booked. Returns false in case no database initialized!
     * @param $roomId
     * @return bool true iff the given roomId has less bookings than capacity, false otherwise.
     */
    public function canRoomBeBooked($roomId)
    {
        if ($this->isHealthy()) {
            $result = $this->database->query("select r.id as roomId, r.capacity, count(b.roomId) as bookings from rooms r, roombooking b where b.roomId = r.id and b.roomId=" . $this->databaseHelper->trimAndMask($roomId));
            if (false === $result) {
                $error = $this->database->errorInfo();
                print "DB-Error\nSQLError=$error[0]\nDBError=$error[1]\nMessage=$error[2]";
                return FALSE;
            }
            while ($row = $result->fetch()) {
                return $row['capacity'] > $row['bookings'];
            }
        }
        return FALSE;
    }

    private function buildQuery($week)
    {
        $query = "SELECT * from `applicants` a";
        // if week == null - return all, else for the given week
        if (isset($week) && strlen($week)) {
            $query .= " WHERE a.week LIKE '%" . trim('' . $week) . "%'";
        }
        $query .= " ORDER by a.week, a.room";

        return $query;
    }

}
