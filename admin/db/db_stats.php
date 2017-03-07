<?php

require '../../vendor/autoload.php';

use \hornherzogen\ConfigurationWrapper;

echo "<h1>Retrieving bookings from DB ....</h1>";

$config = new ConfigurationWrapper();

if ($config->isValidDatabaseConfig()) {

    try {
        $db = new PDO('mysql:host=' . $config->dbhost() . ';dbname=' . $config->dbname(), $config->dbuser(), $config->dbpassword());
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $q = $db->query("select \"applicants\" as name, count(*) from applicants UNION select \"status\" as name, count(*) from status UNION select \"roombooking\" as name, count(*) from roombooking UNION select \"status\" as name, count(*) from status UNION select \"rooms\" as name, count(*) from rooms");
        while ($row = $q->fetch()) {
            print "<h2>Table '$row[name]' has '$row[count]' elements</h2>\n";
        }
    } catch (PDOException $e) {
        print "Unable to connect to db:" . $e->getMessage();
    }

} else {
    echo "You need to edit your database-related parts of the configuration in order to properly connect to the database.";
}