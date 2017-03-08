<?php
use hornherzogen\db\StatusDatabaseReader;

class StatusDatabaseReaderTest
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

}