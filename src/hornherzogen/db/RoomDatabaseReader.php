<?php
declare(strict_types=1);

namespace hornherzogen\db;

class RoomDatabaseReader extends BaseDatabaseWriter
{
    /**
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
            $query .= " order by a.combinedName";

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

    public function listRoomsWithCapacityInWeek($week)
    {
        return $this->listRooms();
    }

    // b) get rooms that have capacity

    /**
     * Retrieve all rooms with their capacity.
     * @return array a simple list of rooms to show in the UI
     */
    public function listRooms()
    {
        $results = array();
        if (self::isHealthy()) {
            $dbResult = $this->database->query("SELECT r.id, r.name, r.capacity from `rooms` r ORDER BY r.name");
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

    // a) get list of all applicants that are not booked per week

    public function listApplicantsWithoutBookingsInWeek($week)
    {
        $results = array();
        if (self::isHealthy()) {

            $query = "SELECT a.* ";
            $query .= " FROM `applicants` a WHERE  ";
            // if week == null - return all, else for the given week
            if (isset($week) && strlen($week)) {
                $query .= " a.week LIKE '%" . trim('' . $week) . "%' AND ";
            }
            $query .= " a.id not in (select applicantId from `roombooking`)";
            $query .= " ORDER BY a.combinedName";

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

    public function listBookingsByRoomNumberAndWeek($roomNumber, $week)
    {
// TODO select * from applicant a, roombooking b where week = $week and $roomNumber = b.id;
        return array();
    }

}
