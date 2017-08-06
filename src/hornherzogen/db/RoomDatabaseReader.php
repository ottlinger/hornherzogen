<?php

declare(strict_types=1);

namespace hornherzogen\db;

class RoomDatabaseReader extends BaseDatabaseWriter
{
    /**
     * Retrieve all rooms with their capacity and booking by week.
     *
     * @param $week week choice, null for both weeks.
     *
     * @return array a simple list of rooms to show in the UI
     */
    public function listRoomBookings($week)
    {
        $results = [];
        if (self::isHealthy()) {
            $query = 'select r.name as roomname, r.capacity, a.combinedName, a.week';
            $query .= ' from roombooking b, applicants a, rooms r where a.id=b.applicantId and r.id=b.roomId';
            // if week == null - return all, else for the given week
            if (isset($week) && strlen($week)) {
                $query .= " AND a.week LIKE '%".trim(''.$week)."%'";
            }
            $query .= ' order by r.name';

            $dbResult = $this->database->query($query);
            $this->databaseHelper->logDatabaseErrors($dbResult, $this->database);

            while ($row = $dbResult->fetch()) {
                //  access all members print "<h2>'$row[name]' has place for $row[capacity] people</h2>\n";
                $results[] = $row;
            }
        }

        return $results;
    }

    /**
     * Returns available rooms with their remaining capacity per week.
     *
     * @param $week if not set available room capacity per room is returned for both weeks.
     *
     * @return mixed available room capacity per room per week.
     */
    public function listAvailableRooms($week)
    {
        $results = [];

        return $results[] = $week;
    }

    /**
     * Retrieve all rooms with their capacity.
     *
     * @return array a simple list of rooms to show in the UI
     */
    public function listRooms()
    {
        $results = [];
        if (self::isHealthy()) {
            $dbResult = $this->database->query('SELECT r.id, r.name, r.capacity from `rooms` r ORDER BY r.name');
            $this->databaseHelper->logDatabaseErrors($dbResult, $this->database);

            while ($row = $dbResult->fetch()) {
                //  access all members print "<h2>'$row[name]' has place for $row[capacity] people</h2>\n";
                $results[] = $row;
            }
        }

        return $results;
    }

    /**
     * Retrieve room by id.
     *
     * @return array a simple list of rooms to show in the UI
     */
    public function getRoomById($roomId)
    {
        $results = [];
        if (self::isHealthy() && is_numeric($roomId) && isset($roomId)) {
            $dbResult = $this->database->query('SELECT * from `rooms` WHERE id = '.$this->databaseHelper->trimAndMask($roomId));
            $this->databaseHelper->logDatabaseErrors($dbResult, $this->database);

            while ($row = $dbResult->fetch()) {
                $results[] = $row;
            }
        }

        return $results;
    }

    // a) get list of all applicants that are not booked per week

    public function listApplicantsWithoutBookingsInWeek($week)
    {
        $results = [];
        if (self::isHealthy()) {
            $query = 'SELECT a.* ';
            $query .= ' FROM `applicants` a WHERE  ';
            // if week == null - return all, else for the given week
            if (isset($week) && strlen($week)) {
                $query .= " a.week LIKE '%".trim(''.$week)."%' AND ";
            }
            $query .= ' a.id not in (select applicantId from `roombooking`)';
            $query .= ' ORDER BY a.combinedName';

            $dbResult = $this->database->query($query);
            $this->databaseHelper->logDatabaseErrors($dbResult, $this->database);

            while ($row = $dbResult->fetch()) {
                $results[] = $this->databaseHelper->fromDatabaseToObject($row);
            }
        }

        return $results;
    }

    /**
     * Returns list of applicants (as ApplicantInput) that have bookings for the given room.
     *
     * @param $roomId room database id
     * @param $week week id
     *
     * @return array of ApplicantInputs (may be empty)
     */
    public function listBookingsByRoomNumberAndWeek($roomId, $week)
    {
        $results = [];
        if (self::isHealthy() && is_numeric($roomId) && isset($roomId) && isset($week) && is_numeric($week)) {
            $query = 'SELECT a.*, b.id as bookingId ';
            $query .= ' FROM `applicants` a, `roombooking` b WHERE  ';
            // if week == null - return all, else for the given week
            if (isset($week) && strlen($week)) {
                $query .= " a.week LIKE '%".trim(''.$week)."%' AND ";
            }
            $query .= ' b.roomId = '.$this->databaseHelper->trimAndMask($roomId);
            $query .= ' AND b.applicantId = a.id ';
            $query .= ' ORDER BY a.combinedName';

            $dbResult = $this->database->query($query);
            $this->databaseHelper->logDatabaseErrors($dbResult, $this->database);

            while ($row = $dbResult->fetch()) {
                // we silently ignore attribute bookingId from above SQL :-D while converting into ApplicantInput elements
                $results[] = $this->databaseHelper->fromDatabaseToObject($row);
            }
        }

        return $results;
    }
}
