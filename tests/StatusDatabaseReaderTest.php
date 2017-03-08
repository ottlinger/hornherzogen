<?php
use hornherzogen\db\StatusDatabaseReader;
use PHPUnit\Framework\TestCase;

class StatusDatabaseReaderTest extends TestCase
{
    private $reader = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $pdo = new PDO('sqlite::memory:');
        self::createTables($pdo);
        $this->reader = new StatusDatabaseReader($pdo);
    }

    private static function createTables($pdo)
    {
        $query = '
            CREATE TABLE status (
              id int PRIMARY KEY NOT NULL,
              name CHAR(50) DEFAULT NULL
            );
        ';

        $dbResult = $pdo->query($query);
        if (false === $dbResult) {
            $error = $pdo->errorInfo();
            print "DB-Error\nSQLError=$error[0]\nDBError=$error[1]\nMessage=$error[2]";
        }
        echo "Table da";

        $pdo->query("INSERT INTO status (id,name) VALUES (1,'APPLIED')");


/*
        INSERT INTO status (name) VALUES ('REGISTERED');
            INSERT INTO status (name) VALUES ('CONFIRMED');
            INSERT INTO status (name) VALUES ('WAITING_FOR_PAYMENT');
            INSERT INTO status (name) VALUES ('CANCELLED');
            INSERT INTO status (name) VALUES ('PAID');
            INSERT INTO status (name) VALUES ('SPAM');
            INSERT INTO status (name) VALUES ('REJECTED');
*/

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

    public function testReadFromDatabaseWithConfiguredDatasourceWorksOnCi() {
        $reader = new StatusDatabaseReader();

        $this->assertFalse($reader->isHealthy());
        $this->assertNull($reader->getById("anyWillGo"));
    }

    // FIXME
    public function testReadStatusFromDatabaseById() {
        $this->assertNull($this->reader->getById("1"));
//        $this->assertEquals(array('id' => "1", 'name' => "APPLIED"), $this->writer->getById("1"));
    }

    // FIXME
    public function testReadStatusFromDatabaseByName() {
        $this->assertNull($this->reader->getByName("APPLIED"));
//        $this->assertEquals(array('id' => "1", 'name' => "APPLIED"), $this->writer->getByName("APPLIED"));
    }


}