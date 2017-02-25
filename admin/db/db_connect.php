<?php
require '../vendor/autoload.php';

use \hornherzogen\ConfigurationWrapper;

echo "<h1>Trying to connect to configured database ....</h1>";

$config = new ConfigurationWrapper();

if ($config->isValidDatabaseConfig()) {

    echo "Connection data is:";
    echo "<ul>";
    echo "<li>HOST: ".$config->dbhost();
    echo "<li>DB-NAME: ".$config->dbname();
    echo "<li>DB-USER: ".strlen($config->dbuser())." characters, starting with ".$config->dbuser()[0];
    echo "<li>DB-PASSWORD: ".strlen($config->dbpassword())." characters";
    echo "</ul>";

    try {
        $db = new PDO('mysql:host=' . $config->dbhost() . ';dbname=' . $config->dbname(), $config->dbuser(), $config->dbpassword());
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $q = $db->query("select curdate() AS foo from dual");
        if (false === $q) {
            $error = $db->errorInfo();
            print "DB-Error\nSQLError=$error[0]\nDBError=$error[1]\nMessage=$error[2]";
        }
        while ($row = $q->fetch()) {
            print "<h2>SUCCESS: Database date is set to '$row[foo]'</h2>\n";
        }

    } catch (PDOException $e) {
        print "Unable to connect to db:" . $e->getMessage();
    }

} else {
    echo "You need to edit your database-related parts of the configuration in order to properly connect to the database.";
}

