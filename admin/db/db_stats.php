<?php

require '../../vendor/autoload.php';

use hornherzogen\ConfigurationWrapper;

echo '<h1>Retrieving rows from all tables of the DB ....</h1>';

$config = new ConfigurationWrapper();

if ($config->isValidDatabaseConfig()) {
    try {
        $db = new PDO('mysql:host='.$config->dbhost().';dbname='.$config->dbname(), $config->dbuser(), $config->dbpassword());
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $q = $db->query('select "applicants" as name, count(*) as count from applicants UNION select "status" as name, count(*) as count from status UNION select "roombooking" as name, count(*) as count from roombooking UNION select "status" as name, count(*) as count from status UNION select "rooms" as name, count(*) as count from rooms');
        while ($row = $q->fetch()) {
            echo "<p>Table '$row[name]' has <strong>$row[count]</strong> elements</p>\n";
        }
    } catch (PDOException $e) {
        echo 'Unable to connect to db:'.$e->getMessage();
    }
} else {
    echo 'You need to edit your database-related parts of the configuration in order to properly connect to the database.';
}
