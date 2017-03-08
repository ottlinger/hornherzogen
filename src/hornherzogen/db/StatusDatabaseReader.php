<?php
namespace hornherzogen\db;


class StatusDatabaseReader extends BaseDatabaseWriter
{
    function getById($id)
    {
        if (!self::isHealthy() || !is_numeric($id)) {
            return NULL;
        }

        $query = 'SELECT * from status';
//        $query = 'SELECT * from status s WHERE s.id = ' . $id . ' ';

        $dbResult = $this->database->query($query);
        if (false === $dbResult) {
            $error = $this->database->errorInfo();
            print "DB-Error\nSQLError=$error[0]\nDBError=$error[1]\nMessage=$error[2]";
        }

        if (0 == $dbResult->rowCount()) {
            echo "0 ergebnisse für id".$id;
            return NULL;
        }

        $results = array();
        while ($row = $dbResult->fetch()) {
            $results[] = $this->fromDatabaseToArray($row);
        }
        return $results;
    }

    function getByName($name)
    {
        if (!self::isHealthy() || isset($name)) {
            return NULL;
        }

        $query = 'SELECT * from status s WHERE s.name = ' . strtoupper($name) . ' ';

        $dbResult = $this->database->query($query);
        if (false === $dbResult) {
            $error = $this->database->errorInfo();
            print "DB-Error\nSQLError=$error[0]\nDBError=$error[1]\nMessage=$error[2]";
        }

        if (0 == $dbResult->rowCount()) {
            echo "0 ergebnisse für id".$name;
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