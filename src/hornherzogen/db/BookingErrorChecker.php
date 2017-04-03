<?php
declare(strict_types=1);

namespace hornherzogen\db;

class BookingErrorChecker extends BaseDatabaseWriter
{
    /**
     * TODO
     * Retrieve all rooms with their capacity and booking by week.
     *
     * @param $week week choice, null for both weeks.
     * @return array a simple list of rooms to show in the UI
     */
    public function listRoomBookings($week)
    {
        $results = array();
        if (self::isHealthy()) {

            $query = "select r.name as roomname, r.capacity, a.combinedName, a.week";
            $query .= " from roombooking b, applicants a, rooms r where a.id=b.applicantId and r.id=b.roomId";
            // if week == null - return all, else for the given week
            if (isset($week) && strlen($week)) {
                $query .= " AND a.week LIKE '%" . trim('' . $week) . "%'";
            }
            $query .= " order by r.name";

            $dbResult = $this->database->query($query);
            if (false === $dbResult) {
                $error = $this->database->errorInfo();
                print "DB-Error\nSQLError=$error[0]\nDBError=$error[1]\nMessage=$error[2]";
            }
            while ($row = $dbResult->fetch()) {
                //  access all members print "<h2>'$row[name]' has place for $row[capacity] people</h2>\n";
                $results[] = $row;
            }
        }
        return $results;
    }

}
