<?php
require '../../vendor/autoload.php';

use hornherzogen\ConfigurationWrapper;
use hornherzogen\db\DatabaseHelper;

$databaseHelper = new DatabaseHelper();

echo "<h1>Establishing DB connection to insert data ....</h1>";

$config = new ConfigurationWrapper();

if ($config->isValidDatabaseConfig()) {

    try {
        $db = new PDO('mysql:host=' . $config->dbhost() . ';dbname=' . $config->dbname(), $config->dbuser(), $config->dbpassword());
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        echo "<h2>Cleanup1: Remove all bookings ...</h2>";
        $result = $db->exec("DELETE FROM `roombooking` WHERE applicantId in ((SELECT a.id AS applicantId FROM `applicants` a where a.vorname like '%Hugo%'))");
        $databaseHelper->logDatabaseErrors($result, $db);

        echo "<h2>Cleanup2: Remove all applicants ...</h2>";
        $result = $db->exec("DELETE FROM `applicants` WHERE vorname='Hugo'");
        $databaseHelper->logDatabaseErrors($result, $db);

        echo "<h2>Insert fake applicant ...</h2>";
        $result = $db->exec("INSERT INTO `applicants` (language,vorname,nachname,week,email,statusId) SELECT 'de','Hugo','Hirsch','week1','foo@bar.de',id from `status` WHERE NAME = 'APPLIED'");
        $databaseHelper->logDatabaseErrors($result, $db);

        echo "<h2>Show all applicants ...</h2>";
        $q = $db->query("SELECT a.vorname, a.nachname, a.created, s.name FROM `applicants` a, `status` s WHERE s.id = a.statusId");
        $databaseHelper->logDatabaseErrors($result, $db);

        while ($row = $q->fetch()) {
            print "<h2>$row[vorname] $row[nachname] created at $row[created] with status $row[name]</h2>\n";
        }

        echo "<h2>Perform room booking ...</h2>";
        $result = $db->exec("INSERT INTO `roombooking` (roomId,applicantId) VALUES ( (SELECT r.id AS roomId FROM `rooms` r where r.name like '%Zimmer1%'), (SELECT a.id AS applicantId FROM `applicants` a where a.vorname like '%Hugo%') )");
        $databaseHelper->logDatabaseErrors($result, $db);

    } catch (PDOException $e) {
        print "Unable to connect to db:" . $e->getMessage();
    }

} else {
    echo "You need to edit your database-related parts of the configuration in order to properly connect to the database.";
}