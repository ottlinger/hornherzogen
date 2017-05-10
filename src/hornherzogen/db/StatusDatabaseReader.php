<?php
declare(strict_types=1);

namespace hornherzogen\db;

class StatusDatabaseReader extends BaseDatabaseWriter
{
    function getById($databaseId)
    {
        if (!self::isHealthy() || !is_numeric($databaseId)) {
            return NULL;
        }

        return $this->getResultsFromDatabase('SELECT * from status s WHERE s.id = "' . $databaseId . '"');
    }

    private function getResultsFromDatabase($query)
    {
        $dbResult = $this->database->query($query);
        $this->databaseHelper->logDatabaseErrors($dbResult, $this->database);

        $results = array();
        while ($row = $dbResult->fetch()) {
            $results[] = $this->fromDatabaseToArray($row);
        }

        return empty($results) ? NULL : $results;
    }

    public function fromDatabaseToArray($row)
    {
        if (isset($row)) {
            return array(
                'id' => $row['id'],
                'name' => $row['name']
            );
        }
        return NULL;
    }

    public function getByName($name)
    {
        if (!self::isHealthy() || !isset($name)) {
            return NULL;
        }

        return $this->getResultsFromDatabase('SELECT * from status s WHERE s.name = "' . strtoupper($name) . '"');
    }

    public function getAll()
    {
        if (!self::isHealthy()) {
            return NULL;
        }
        return $this->getResultsFromDatabase('SELECT * from status s ORDER BY s.name ASC');
    }

    public function adminAdditionalTextForState($name)
    {
        switch ($name) {
            case "BOOKED":
                return " (Status wird erzeugt durch Batchaussenden der Bestätigungsmails - nicht einstellen)";

            case "PAID":
                return " (sobald Zahlung eingangen)";

            case "WAITING_FOR_PAYMENT":
                return " (sendet Zahlungsaufforderung per Mail raus!)";

            case "APPLIED":
                return " (Standard nach erfolgter Anmeldung, kein Mailversand)";

            default:
                return '';
        }
    }
}