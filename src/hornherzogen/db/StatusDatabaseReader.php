<?php
namespace hornherzogen\db;


class StatusDatabaseReader extends BaseDatabaseWriter
{
    function getById($id)
    {
        if (!self::isHealthy()) {
            return NULL;
        }

        $query = 'SELECT * from `status` s WHERE s.id = "' . $id . '" ';

        $dbResult = $this->database->query($query);
        if (false === $dbResult) {
            $error = $this->database->errorInfo();
            print "DB-Error\nSQLError=$error[0]\nDBError=$error[1]\nMessage=$error[2]";
        }

        if (0 == $dbResult->rowCount()) {
            return NULL;
        }

        $results = array();
        while ($row = $dbResult->fetch()) {
            $results[] = $this->fromDatabaseToArray($row);
        }
        return $results;
    }

    function fromDatabaseToArray($row)
    {
        if (isset($row)) {
            return array(
                'id' => $row['id'],
                'name' => $row['name']
            );
        }
        return NULL;
    }

}