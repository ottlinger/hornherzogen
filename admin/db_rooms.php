<?php
require '../vendor/autoload.php';

use \hornherzogen\ConfigurationWrapper;

echo "<p>Retrieving rooms from DB ....</p>";

if (ConfigurationWrapper::isValidDatabaseConfig()) {

    try {
        $db = new PDO('mysql:host=' . ConfigurationWrapper::dbhost() . ';dbname=' . ConfigurationWrapper::dbname(), ConfigurationWrapper::dbuser(), ConfigurationWrapper::dbpassword());
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $q = $db->query("SELECT r.name, r.capacity FROM `rooms` r");
        while ($row = $q->fetch()) {
            print "<h2>'$row[name]' has place for $row[capacity] people</h2>\n";
        }
    } catch (PDOException $e) {
        print "Unable to connect to db:" . $e->getMessage();
    }

} else {
    echo "You need to edit your database-related parts of the configuration in order to properly connect to the database.";
}

