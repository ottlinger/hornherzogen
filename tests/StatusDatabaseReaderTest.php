<?php
use hornherzogen\db\StatusDatabaseReader;
use PHPUnit\Framework\TestCase;

class StatusDatabaseReaderTest extends TestCase
{
    private static $pdo = null;
    private $reader = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
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
        if (false === $dbResult) {
            $error = $pdo->errorInfo();
            print "DB-Error\nSQLError=$error[0]\nDBError=$error[1]\nMessage=$error[2]";
        }

        $dbResult = $pdo->query("INSERT INTO status (id,name) VALUES (1,'APPLIED')");
        $dbResult = $pdo->query("INSERT INTO status (id,name) VALUES (2,'REGISTERED')");
        $dbResult = $pdo->query("INSERT INTO status (id,name) VALUES (3,'CONFIRMED')");
        $dbResult = $pdo->query("INSERT INTO status (id,name) VALUES (4,'WAITING_FOR_PAYMENT')");
        $dbResult = $pdo->query("INSERT INTO status (id,name) VALUES (5,'CANCELLED')");
        $dbResult = $pdo->query("INSERT INTO status (id,name) VALUES (6,'PAID')");
        $dbResult = $pdo->query("INSERT INTO status (id,name) VALUES (7,'SPAM')");
        $dbResult = $pdo->query("INSERT INTO status (id,name) VALUES (8,'REJECTED')");
        if (false === $dbResult) {
            $error = $pdo->errorInfo();
            print "DB-Error\nSQLError=$error[0]\nDBError=$error[1]\nMessage=$error[2]";
        }

        $dbResult = $pdo->query("SELECT * FROM status");
        if (false === $dbResult) {
            $error = $pdo->errorInfo();
            print "DB-Error\nSQLError=$error[0]\nDBError=$error[1]\nMessage=$error[2]";
        }

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
}