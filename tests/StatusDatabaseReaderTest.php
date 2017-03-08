<?php
use PHPUnit\Framework\TestCase;
use hornherzogen\db\StatusDatabaseReader;

class StatusDatabaseReaderTest
{
    private $writer = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->writer = new StatusDatabaseReader(new PDO('sqlite::memory:'));
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

    public function testDatabaseConnectionIsHealthyDueToSqlite() {
        $this->assertTrue($this->writer->isHealthy());
    }

}