<?php

use hornherzogen\db\DatabaseHelper;
use hornherzogen\db\BookingDatabaseReader;
use PHPUnit\Framework\TestCase;

class BookingDatabaseReaderTest extends TestCase
{
    // TODO fix test setup for real bookingdatabase reader

    private static $pdo = null;
    private $reader = null;
    private $databaseHelper;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->databaseHelper = new DatabaseHelper();
        self::$pdo = $this->createTables();
        $this->reader = new BookingDatabaseReader(self::$pdo);
    }

    private function createTables()
    {
        if (isset(self::$pdo)) {
            return self::$pdo;
        }
        $pdo = new PDO('sqlite::memory:');

        $query = '
            CREATE TABLE status (
              id int PRIMARY KEY  NOT NULL,
              name CHAR(50) DEFAULT NULL
            );
        ';

        $dbResult = $pdo->query($query);
        $this->databaseHelper->logDatabaseErrors($dbResult, $pdo);

        $dbResult = $pdo->query("INSERT INTO status (id,name) VALUES (1,'APPLIED')");
        $dbResult = $pdo->query("INSERT INTO status (id,name) VALUES (2,'REGISTERED')");
        $dbResult = $pdo->query("INSERT INTO status (id,name) VALUES (3,'CONFIRMED')");
        $dbResult = $pdo->query("INSERT INTO status (id,name) VALUES (4,'WAITING_FOR_PAYMENT')");
        $dbResult = $pdo->query("INSERT INTO status (id,name) VALUES (5,'CANCELLED')");
        $dbResult = $pdo->query("INSERT INTO status (id,name) VALUES (6,'PAID')");
        $dbResult = $pdo->query("INSERT INTO status (id,name) VALUES (7,'SPAM')");
        $dbResult = $pdo->query("INSERT INTO status (id,name) VALUES (8,'REJECTED')");
        $this->databaseHelper->logDatabaseErrors($dbResult, $pdo);

        $dbResult = $pdo->query("SELECT * FROM status");
        $this->databaseHelper->logDatabaseErrors($dbResult, $pdo);
        return $pdo;
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        $this->reader = null;
    }

    /**
     * Test instance of.
     *
     * @test
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf('hornherzogen\db\BookingDatabaseReader', $this->reader);
    }

    public function returnEmptyBookingsWithoutDatabase() {
        $this->assertEmpty($this->reader->getForApplicant(4711));
    }
}
