<?php

use hornherzogen\db\DatabaseHelper;
use hornherzogen\db\RoomDatabaseWriter;
use PHPUnit\Framework\TestCase;

class RoomDatabaseWriterTest extends TestCase
{
    private static $pdo = null;
    private $writer = null;
    private $databaseHelper;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->databaseHelper = new DatabaseHelper();
        self::$pdo = $this->createTables();
        // TODO        $this->writer = new RoomDatabaseWriter(self::$pdo);
        $this->writer = new RoomDatabaseWriter(null);
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

        $dbResult = $pdo->query('SELECT * FROM status');
        $this->databaseHelper->logDatabaseErrors($dbResult, $pdo);

        return $pdo;
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        $this->writer = null;
    }

    /**
     * Test instance of.
     *
     * @test
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf('hornherzogen\db\RoomDatabaseWriter', $this->writer);
    }

    public function testPerformBooking()
    {
        $this->assertNull($this->writer->performBooking(1, 2));
    }

    public function testDeleteForApplicantId()
    {
        $this->assertNull($this->writer->deleteForApplicantId(123));
    }

    public function testCanRoomBeBooked()
    {
        $this->assertFalse($this->writer->canRoomBeBooked(345));
    }
}
