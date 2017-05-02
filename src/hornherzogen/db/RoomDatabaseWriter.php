<?php
declare(strict_types=1);

namespace hornherzogen\db;

class RoomDatabaseWriter extends BaseDatabaseWriter
{
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
            $this->databaseHelper->logDatabaseErrors($result, $this->database);

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
            $this->databaseHelper->logDatabaseErrors($result, $this->database);
        }
        return NULL;
    }

    /**
     * Retrieve if the given roomId can be booked. Returns false in case no database initialized!
     * @param $roomId
     * @return bool true iff the given roomId has less bookings than 2 * capacity, false otherwise.
     */
    public function canRoomBeBooked($roomId)
    {
        if ($this->isHealthy()) {
            $result = $this->database->query("select r.id as roomId, r.capacity, count(b.roomId) as bookings from rooms r, roombooking b where b.roomId = r.id and b.roomId=" . $this->databaseHelper->trimAndMask($roomId));
            $this->databaseHelper->logDatabaseErrors($result, $this->database);

            while ($row = $result->fetch()) {
                // 2 per week, thus multiply by 2
                return (2 * $row['capacity']) > $row['bookings'];
            }
        }
        return FALSE;
    }

}
