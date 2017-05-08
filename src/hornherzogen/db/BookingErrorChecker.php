<?php
declare(strict_types=1);

namespace hornherzogen\db;

class BookingErrorChecker extends BaseDatabaseWriter
{
    /**
     * Retrieve all rooms with their capacity and booking by week.
     *
     * @param $week week choice, null for both weeks.
     * @return array a simple list of rooms to show in the UI
     */
    public function listRoomBookings($week = NULL)
    {
        $results = array();
        if (self::isHealthy()) {

            $query = "select b.id, r.name as roomname, r.capacity, a.combinedName, a.week, b.applicantId";
            $query .= " from roombooking b, applicants a, rooms r where a.id=b.applicantId and r.id=b.roomId";
            // if week == null - return all, else for the given week
            if (isset($week) && strlen($week)) {
                $query .= " AND a.week LIKE '%" . trim('' . $week) . "%'";
            }
            $query .= " order by a.week, r.name";

            // select r.applicantId, count(*) as count from roombooking r group by r.applicantId having count(*)>1

            $dbResult = $this->database->query($query);
            $this->databaseHelper->logDatabaseErrors($dbResult, $this->database);

            while ($row = $dbResult->fetch()) {
                //  access all members print "<h2>'$row[name]' has place for $row[capacity] people</h2>\n";
                $results[] = $row;
            }
        }
        return $results;
    }

    function removeById($bookingId)
    {
        if ($this->isHealthy() && isset($bookingId) && strlen($bookingId)) {
            return $this->database->exec("DELETE from `roombooking` WHERE id = " . $this->databaseHelper->makeSQLCapable($bookingId, $this->database));
        }
        return 0;
    }

    public function listDoubleBookings()
    {
        $results = array();
        if (self::isHealthy()) {
            $query = "select r.applicantId, count(*) as count from roombooking r group by r.applicantId having count(*)>1";
            $dbResult = $this->database->query($query);
            $this->databaseHelper->logDatabaseErrors($dbResult, $this->database);

            while ($row = $dbResult->fetch()) {
                $results[] = $row;
            }
        }
        return $results;
    }

    public function listOverbookedBookings()
    {
        $results = array();
        if (self::isHealthy()) {
            // Issue #96: multiply by 2 for all weeks
            $query = "select b.roomId, count(*) as bookingcount, r.capacity, r.name from roombooking b, rooms r where r.id=b.roomId group by b.roomId having bookingcount>(2*r.capacity);";
            $dbResult = $this->database->query($query);
            $this->databaseHelper->logDatabaseErrors($dbResult, $this->database);

            while ($row = $dbResult->fetch()) {
                $results[] = $row;
            }
        }
        return $results;
    }

}
