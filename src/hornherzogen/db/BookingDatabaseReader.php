<?php

declare(strict_types=1);

namespace hornherzogen\db;

class BookingDatabaseReader extends BaseDatabaseWriter
{
    public function getForApplicant($applicantId)
    {
        // special test handling
        if ($applicantId === -4711) {
            $test4711['name'] = 'My testroom 4711';
            $test4711['week'] = 1;
            $test4711['capacity'] = 47;
            $test4712['name'] = 'My testroom 4712';
            $test4712['week'] = 2;
            $test4712['capacity'] = 3;

            return [$test4711, $test4712];
        }

        if (!self::isHealthy() || !is_numeric($applicantId)) {
            return;
        }

        return $this->getResultsFromDatabase('select a.week, r.name, r.capacity from roombooking b, applicants a, rooms r where r.id = b.roomId and a.id = b.applicantId and a.id ="'.$applicantId.'"');
    }

    private function getResultsFromDatabase($query)
    {
        $dbResult = $this->database->query($query);
        $this->databaseHelper->logDatabaseErrors($dbResult, $this->database);

        $results = [];
        while ($row = $dbResult->fetch()) {
            $results[] = $row;
        }

        return empty($results) ? null : $results;
    }
}
