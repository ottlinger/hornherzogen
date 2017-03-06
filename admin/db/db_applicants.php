<?php
require '../../vendor/autoload.php';

use \hornherzogen\ConfigurationWrapper;

echo "<h1>List all applicants ....</h1>";

$config = new ConfigurationWrapper();

if ($config->isValidDatabaseConfig()) {

    try {
        $db = new PDO('mysql:host=' . $config->dbhost() . ';dbname=' . $config->dbname(), $config->dbuser(), $config->dbpassword());
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // STATS
        echo "<h3>Applicants by week and status</h3>";
        $q = $db->query("SELECT s.name, a.week, a.statusId, count(*) AS howmany from `status` s, `applicants` a WHERE a.statusId=s.id GROUP BY a.statusId, a.week ORDER BY a.week");
        if (false === $q) {
            $error = $db->errorInfo();
            print "DB-Error\nSQLError=$error[0]\nDBError=$error[1]\nMessage=$error[2]";
        }
        $rowNum = 0;
        while ($row = $q->fetch()) {
            $rowNum++;
            print "<h2>($rowNum) '$row[name]' in week $row[week] for $row[howmany] applicants</h2>\n";
        }

        // ALL with status
        echo "<h2>Show all applicants and their status ...</h2>";
        $q = $db->query("SELECT a.vorname, a.nachname, a.created, s.name FROM `applicants` a, `status` s WHERE s.id = a.statusId ORDER BY s.name");
        if (false === $q) {
            $error = $db->errorInfo();
            print "DB-Error\nSQLError=$error[0]\nDBError=$error[1]\nMessage=$error[2]";
        }

        echo "<h1>Currently there are ".$q->rowCount()." applicants in the database</h1>";

        $rowNum = 0;
        while ($row = $q->fetch()) {
            $rowNum++;
            print "<h2>($rowNum) $row[vorname] $row[nachname] created at $row[created] with status $row[name]</h2>\n";
        }

    } catch (PDOException $e) {
        print "Unable to connect to db:" . $e->getMessage();
    }


    // retrieve via helper
    print "<h1>Use DatabaseWriter</h1>";
    $writer = new \hornherzogen\db\ApplicantDatabaseWriter();

    $applicants = $writer->getAllByWeek();
    echo "<table>";
    echo "<thead>";
    echo "<tr>";
    echo "<td>DB-Id</td>";
    echo "<td>Vorname</td>";
    echo "<td>Nachname</td>";
    echo "<td>E-Mail</td>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    foreach ($applicants as  $applicant) {
        echo "<tr>";
        echo "<td>".$applicant->getPersistenceId()."</td>";
        echo "<td>".$applicant->getFirstname()."</td>";
        echo "<td>".$applicant->getLastname()."</td>";
        echo "<td>".$applicant->getMail()."</td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";

} else {
    echo "You need to edit your database-related parts of the configuration in order to properly connect to the database.";
}

