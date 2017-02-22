<?php
require '../vendor/autoload.php';

use \hornherzogen\ConfigurationWrapper;

echo "<p>Establishing DB connection ....</p>";

if (ConfigurationWrapper::isValidDatabaseConfig()) {

    try {
        $db = new PDO('mysql:host=' . ConfigurationWrapper::dbhost() . ';dbname=' . ConfigurationWrapper::dbname(), ConfigurationWrapper::dbuser(), ConfigurationWrapper::dbpassword());
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $result = $db->exec("INSERT INTO `applicants` (vorname,nachname,statusId) SELECT 'Hugo','Hirsch',id from `status` WHERE NAME = 'APPLIED'");
        if (false === $result) {
            $error = $db->errorInfo();
            print "DB-Error\nSQLError=$error[0]\nDBError=$error[1]\nMessage=$error[2]";
        }

        $q = $db->query("SELECT a.vorname, a.nachname, s.name FROM `applicants` a, `status` s WHERE s.id = a.statusId");
        while ($row = $q->fetch()) {
            print "<h2>$row[vorname] $row[nachname] created at $row[created] with status $row[name]</h2>\n";
        }

        $result = $db->exec("INSERT INTO `roombooking` (roomId,applicantId) VALUES ( (SELECT r.id AS roomId FROM `rooms` r where r.name like '%Zimmer1%' ), (SELECT a.id AS applicantId FROM `applicants` a where a.vorname like '%Hugo%') )");
        if (false === $result) {
            $error = $db->errorInfo();
            print "DB-Error\nSQLError=$error[0]\nDBError=$error[1]\nMessage=$error[2]";
        }

        //        $result = $db->exec("DELETE FROM `applicants` WHERE vorname='Hugo'");

    } catch (PDOException $e) {
        print "Unable to connect to db:" . $e->getMessage();
    }

} else {
    echo "You need to edit your database-related parts of the configuration in order to properly connect to the database.";
}

