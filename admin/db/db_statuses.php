<?php
require '../../vendor/autoload.php';

use \hornherzogen\ConfigurationWrapper;

echo "<h1>List all statuses and count bookings for it ....</h1>";

$config = new ConfigurationWrapper();

if ($config->isValidDatabaseConfig()) {

    try {
        $db = new PDO('mysql:host=' . $config->dbhost() . ';dbname=' . $config->dbname(), $config->dbuser(), $config->dbpassword());
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        echo "<h2>Show all applicants and their status ...</h2>";
        // ALL values
        echo "<h3>All statuses</h3>";
        $q = $db->query("SELECT s.name FROM `status` s ORDER BY s.name");
        if (false === $q) {
            $error = $db->errorInfo();
            print "DB-Error\nSQLError=$error[0]\nDBError=$error[1]\nMessage=$error[2]";
        }
        echo "<h3>Currently there are ".$q->rowCount()." status values available in the database</h3>";

        $rowNum = 0;
        while ($row = $q->fetch()) {
            $rowNum++;
            print "<h2>($rowNum) $row[name]</h2>\n";
        }

        // COUNT from the applicants table
        echo "<h3>Statuses grouped by applicants in the database</h3>";
        $q = $db->query("SELECT s.name, a.statusId, count(*) AS howmany from `status` s, `applicants` a WHERE a.statusId=s.id GROUP BY a.statusId");
        if (false === $q) {
            $error = $db->errorInfo();
            print "DB-Error\nSQLError=$error[0]\nDBError=$error[1]\nMessage=$error[2]";
        }
        $rowNum = 0;
        while ($row = $q->fetch()) {
            $rowNum++;
            print "<h2>($rowNum) '$row[name]' set for $row[howmany] applicants</h2>\n";
        }


    } catch (PDOException $e) {
        print "Unable to connect to db:" . $e->getMessage();
    }

} else {
    echo "You need to edit your database-related parts of the configuration in order to properly connect to the database.";
}

