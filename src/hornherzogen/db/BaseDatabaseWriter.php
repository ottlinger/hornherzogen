<?php
namespace hornherzogen\db;
use PDO;
use PDOException;
use hornherzogen\ConfigurationWrapper;

class BaseDatabaseWriter
{
    private $config;
    protected $database;
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