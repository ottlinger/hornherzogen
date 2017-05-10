<?php

use hornherzogen\db\DatabaseHelper;
use hornherzogen\db\StatusDatabaseReader;
use PHPUnit\Framework\TestCase;

class StatusDatabaseReaderTest extends TestCase
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
        $this->reader = new StatusDatabaseReader(self::$pdo);
    }

    private function createTables()
    {
        if (isset(self::$pdo)) {
            return self::$pdo;
        }
        echo "InitDB for statuses.";
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

        echo ">>>";
        while ($row = $dbResult->fetch()) {
            echo $row['id'] . "/" . $row['name'];
        }
        echo "<<<";

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
        $this->assertInstanceOf('hornherzogen\db\StatusDatabaseReader', $this->reader);
    }

    public function testDatabaseConnectionIsHealthyDueToSqlite()
    {
        $this->assertTrue($this->reader->isHealthy());
    }

    public function testRowConversionWithNullArguments()
    {
        $this->assertNull($this->reader->fromDatabaseToArray(NULL));
    }

    public function testRowConversionWithRowGiven()
    {
        $row = array();
        $row['id'] = "4711";
        $row['name'] = "TESTSTATE";

        $this->assertEquals(array('id' => "4711", 'name' => "TESTSTATE"), $this->reader->fromDatabaseToArray($row));
    }

    public function testReadStatusFromDatabaseById()
    {
        $this->assertTrue($this->reader->isHealthy());
        $this->assertEquals(array(array('id' => "1", 'name' => "APPLIED")), $this->reader->getById(1));
    }

    public function testReadStatusFromDatabaseByIdWithBogusInput()
    {
        $this->assertTrue($this->reader->isHealthy());
        $this->assertNull($this->reader->getById("ThisIsNotANumber"));
    }

    public function testReadStatusFromDatabaseByName()
    {
        $this->assertTrue($this->reader->isHealthy());
        $this->assertEquals(array(array('id' => "1", 'name' => "APPLIED")), $this->reader->getByName("APPLIED"));
    }

    public function testGetByNameWithNoName()
    {
        $this->assertNull($this->reader->getByName(NULL));
    }

    public function testGetAllIsNullWithoutDatabase()
    {
        $reader = new StatusDatabaseReader();
        $this->assertNull($reader->getAll());
    }

    public function testGetAllSortedByName()
    {
        $allStatuses = $this->reader->getAll();
        $this->assertNotNull($allStatuses);
        $this->assertCount(8, $allStatuses);
        $this->assertEquals("APPLIED", $allStatuses[0]['name']);
        $this->assertEquals("WAITING_FOR_PAYMENT", $allStatuses[7]['name']);
    }

    public function testAdditionalNotesMappingUnknownName()
    {
        $this->assertEquals('', $this->reader->adminAdditionalTextForState("LALELOU"));
        $this->assertEquals(' (Standard nach erfolgter Anmeldung, kein Mailversand)', $this->reader->adminAdditionalTextForState("APPLIED"));
    }

    public function testAdditionalNotesMappingBooked()
    {
        $this->assertEquals(' (Status wird erzeugt durch Batchaussenden der Bestätigungsmails - nicht einstellen)', $this->reader->adminAdditionalTextForState("BOOKED"));
    }

    public function testAdditionalNotesMappingPaid()
    {
        $this->assertEquals(' (sobald Zahlung eingangen)', $this->reader->adminAdditionalTextForState("PAID"));
    }

    public function testAdditionalNotesMappingWaitingForPayment()
    {
        $this->assertEquals(' (sendet Zahlungsaufforderung per Mail raus!)', $this->reader->adminAdditionalTextForState("WAITING_FOR_PAYMENT"));
    }

}