<?php
require '../vendor/autoload.php';

use \hornherzogen\ConfigurationWrapper;

if (ConfigurationWrapper::debug()) {

    echo "<p>Establishing DB connection ....</p>";

    if (ConfigurationWrapper::isValidDatabaseConfig()) {

        try {
            $db = new PDO('mysql:host=' . ConfigurationWrapper::dbhost() . ';dbname=' . ConfigurationWrapper::dbname(), ConfigurationWrapper::dbuser(), ConfigurationWrapper::dbpassword());
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $result = $db->exec("INSERT INTO applicants (vorname,nachname) VALUES ('Hugo','Hirsch')");
            if (false === $result) {
                $error = $db->errorInfo();
                print "DB-Error\nSQLError=$error[0]\nDBError=$error[1]\nMessage=$error[2]";
            }

            $q = $db->query("SELECT vorname,nachname,created from applicants");
            while ($row = $q->fetch()) {
                print "<h2>$row[vorname] $row[nachname] created at $row[created]</h2>\n";
            }

            $result = $db->exec("DELETE from applicants where vorname='Hugo'");

        } catch (PDOException $e) {
            print "Unable to connect to db:" . $e->getMessage();
        }

    } else {
        echo "You need to edit your database-related parts of the configuration in order to properly connect to the database.";
    }

} else {
    echo "<p>Welcome - nothing to do now.</p>";
}
