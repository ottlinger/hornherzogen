<?php

declare(strict_types=1);

namespace hornherzogen\db;

use hornherzogen\ConfigurationWrapper;
use hornherzogen\FormHelper;
use PDO;
use PDOException;

class BaseDatabaseWriter
{
    protected $database;
    protected $formHelper;
    protected $databaseHelper;
    protected $healthy = null;
    private $config;

    public function __construct($databaseConnection = null)
    {
        $this->config = new ConfigurationWrapper();
        $this->formHelper = new FormHelper();
        $this->databaseHelper = new DatabaseHelper();

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
            echo 'Illegal DB configuration, will fallback to in memory SQLite to ease testing.';
            $this->healthy = false;

            return;
        }

        try {
            $this->database = new PDO('mysql:host='.$this->config->dbhost().';dbname='.$this->config->dbname(), $this->config->dbuser(), $this->config->dbpassword());
            $this->database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->healthy = true;
        } catch (PDOException $e) {
            echo 'Unable to connect to database please check your configuration settings! Message was: '.$e->getMessage();
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
}
