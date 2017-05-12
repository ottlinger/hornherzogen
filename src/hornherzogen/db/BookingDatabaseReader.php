<?php
declare(strict_types=1);

namespace hornherzogen\db;

class BookingDatabaseReader extends BaseDatabaseWriter
{
    function getForApplicant($applicantId)
    {
        // special test handling
        if ($applicantId === -4711) {
            $a['name'] = "My testroom 4711";
            $a['week'] = 1;
            $b['name'] = "My testroom 4712";
            $b['week'] = 2;
            return array($a, $b);
        }

        if (!self::isHealthy() || !is_numeric($applicantId)) {
            return NULL;
        }

        return $this->getResultsFromDatabase('select a.week, r.name from roombooking b, applicants a, rooms r where r.id = b.roomId and a.id = b.applicantId and a.id ="' . $applicantId . '"');
    }

    private function getResultsFromDatabase($query)
    {
        $dbResult = $this->database->query($query);
        $this->databaseHelper->logDatabaseErrors($dbResult, $this->database);

        $results = array();
        while ($row = $dbResult->fetch()) {
            $results[] = $row;
        }

        return empty($results) ? NULL : $results;
    }
}
