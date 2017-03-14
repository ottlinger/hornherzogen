<?php
namespace hornherzogen\db;

use hornherzogen\ConfigurationWrapper;
use hornherzogen\FormHelper;
use PDO;
use PDOException;

class BaseDatabaseWriter
{
    protected $database;
    protected $formHelper;
    private $config;
    private $healthy = NULL;

    function __construct($databaseConnection = NULL)
    {
        $this->config = new ConfigurationWrapper();
        $this->formHelper = new FormHelper();

        if (isset($databaseConnection)) {
            $this->database = $databaseConnection;
            $this->healthy = true;
            return;
        }
        $this->validateDatabaseConnectionFailIfIncorrect();
    }

    private function validateDatabaseConnectionFailIfIncorrect()
    {
        if (!$this->config->isValidDatabaseConfig()) {
            echo "Illegal DB configuration, will fallback to in memory SQLite to ease testing.";
            $this->healthy = false;
            return;
        }

        try {
            $this->database = new PDO('mysql:host=' . $this->config->dbhost() . ';dbname=' . $this->config->dbname(), $this->config->dbuser(), $this->config->dbpassword());
            $this->database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->healthy = true;
        } catch (PDOException $e) {
            print "Unable to connect to database please check your configuration settings! Message was: " . $e->getMessage();
            $this->healthy = false;
        }
    }

    /**
     * @return bool true, iff the underlying database connection works fine, false otherwise
     */
    public function isHealthy()
    {
        return boolval($this->healthy);
    }

    public function makeSQLCapable($input)
    {
        if (isset($input)) {
            if (isset($this->database)) {
                $mask = $this->database->quote($input);
            } else {
                $mask = "'" . $input . "'";
            }
            $mask = strtr($mask, array('_' => '\_', '%' => '\%'));
            return $mask;
        }
        return $input;
    }

}