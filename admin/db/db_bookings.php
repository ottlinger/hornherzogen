<?php
require '../../vendor/autoload.php';

use \hornherzogen\ConfigurationWrapper;

echo "<h1>Retrieving bookings from DB ....</h1>";

echo "<p>If you do not see anything, there are no room bookings yet.</p>";

$config = new ConfigurationWrapper();

if ($config->isValidDatabaseConfig()) {

    try {
        $db = new PDO('mysql:host=' . $config->dbhost() . ';dbname=' . $config->dbname(), $config->dbuser(), $config->dbpassword());
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $q = $db->query("SELECT r.roomId, r.applicantId FROM `roombooking` r");
        while ($row = $q->fetch()) {
            print "<h2>Room '$row[roomId]' is booked by person '$row[applicantId]'</h2>\n";
        }
    } catch (PDOException $e) {
        print "Unable to connect to db:" . $e->getMessage();
    }

} else {
    echo "You need to edit your database-related parts of the configuration in order to properly connect to the database.";
}