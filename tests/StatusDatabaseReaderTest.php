<?php
use hornherzogen\db\StatusDatabaseReader;
use PHPUnit\Framework\TestCase;

class StatusDatabaseReaderTest extends TestCase
{
    private $writer = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $pdo = new PDO('sqlite::memory:');
        self::createTables($pdo);
        $this->writer = new StatusDatabaseReader($pdo);
    }

    private static function createTables($pdo)
    {
        $query = '
            CREATE TABLE IF NOT EXISTS `status` (
              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `name` varchar(50) COLLATE utf8_bin DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1;
            
            INSERT INTO status (name) VALUES (\'APPLIED\');
            INSERT INTO status (name) VALUES (\'REGISTERED\');
            INSERT INTO status (name) VALUES (\'CONFIRMED\');
            INSERT INTO status (name) VALUES (\'WAITING_FOR_PAYMENT\');
            INSERT INTO status (name) VALUES (\'CANCELLED\');
            INSERT INTO status (name) VALUES (\'PAID\');
            INSERT INTO status (name) VALUES (\'SPAM\');
            INSERT INTO status (name) VALUES (\'REJECTED\');
        ';

        $pdo->query($query);
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
        $this->assertInstanceOf('hornherzogen\db\StatusDatabaseReader', $this->writer);
    }

    public function testDatabaseConnectionIsHealthyDueToSqlite()
    {
        $this->assertTrue($this->writer->isHealthy());
    }

    public function testRowConversionWithNullArguments()
    {
        $this->assertNull($this->writer->fromDatabaseToArray(NULL));
    }

    public function testRowConversionWithRowGiven()
    {
        $row = array();
        $row['id'] = "4711";
        $row['name'] = "TESTSTATE";

        $this->assertEquals(array('id' => "4711", 'name' => "TESTSTATE"), $this->writer->fromDatabaseToArray($row));
    }


}