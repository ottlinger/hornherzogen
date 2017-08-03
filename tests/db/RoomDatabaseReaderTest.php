<?php

use hornherzogen\db\DatabaseHelper;
use hornherzogen\db\RoomDatabaseReader;
use PHPUnit\Framework\TestCase;

class RoomDatabaseReaderTest extends TestCase
{
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
        // TODO        $this->reader = new RoomDatabaseReader(self::$pdo);
        $this->reader = new RoomDatabaseReader(NULL);
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
        $this->assertInstanceOf('hornherzogen\db\RoomDatabaseReader', $this->reader);
    }

    public function testListOfRoomBookingsWithWeek()
    {
        $this->assertEmpty($this->reader->listRoomBookings(1));
    }

    public function testListRooms()
    {
        $this->assertEmpty($this->reader->listRooms());
    }

    public function testGetRoomById()
    {
        $this->assertEmpty($this->reader->getRoomById(4711));
    }

    public function testListApplicantsWithoutBookingsInWeek()
    {
        $this->assertEmpty($this->reader->listApplicantsWithoutBookingsInWeek(1));
    }

    public function testListBookingsByRoomNumberAndWeek()
    {
        $this->assertEmpty($this->reader->listBookingsByRoomNumberAndWeek(1, 23));
    }

    public function testlistAvailableRooms()
    {
        $this->assertNotEmpty($this->reader->listAvailableRooms("w1"));
        $this->assertNotEmpty($this->reader->listAvailableRooms("w2"));
        $this->assertEmpty($this->reader->listAvailableRooms(NULL));
    }
}
