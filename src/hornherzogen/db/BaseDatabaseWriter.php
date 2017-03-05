<?php
namespace hornherzogen;
use PDO;
use PDOException;

class BaseDatabaseWriter
{
    private $config;
    private $database;
    private $healthy = NULL;

    function __construct()
    {
        $this->config = new ConfigurationWrapper();
        $this->validateDatabaseConnectionFailIfIncorrect();
    }

    private function validateDatabaseConnectionFailIfIncorrect() {
        try {
            $this->database = new PDO('mysql:host=' . $this->config->dbhost() . ';dbname=' . $this->config->dbname(), $this->config->dbuser(), $this->config->dbpassword());
            $this->database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            /*
            $q = $db->query("select curdate() AS foo from dual");
            if (false === $q) {
                $error = $db->errorInfo();
                print "DB-Error\nSQLError=$error[0]\nDBError=$error[1]\nMessage=$error[2]";
            }
            while ($row = $q->fetch()) {
                print "<h2>SUCCESS: Database date is set to '$row[foo]'</h2>\n";
            }

            */

            $this->healthy = true;

        } catch (PDOException $e) {
            print "Unable to connect to database please check your configuration settings! Message was: " . $e->getMessage();
        }
    }

    /**
     * @return bool true, iff the underlying database connection works fine, false otherwise
     */
    public function isHealthy()
    {
        return boolval($this->healthy);
    }

    // TODO
    // contains the DB access and helper methods e.g.
    // 1)exists(firstname, lastname) to properly calculate the combinedName as firstname + timestamp + lastname to avoid disambiguities
    // 2)persist()
    // 3)getByName(....) - makes a search in combinedName column
    // call $this->setFullName(''); with a salt if a user with that first/last name exists already

    // Tests auf DB-Ebene:
    // https://phpunit.de/manual/current/en/database.html
}