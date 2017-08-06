<?php

use hornherzogen\db\BaseDatabaseWriter;
use PHPUnit\Framework\TestCase;

class BaseDatabaseWriterTest extends TestCase
{
    private $writer = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->writer = new BaseDatabaseWriter(new PDO('sqlite::memory:'));
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
        $this->assertInstanceOf('hornherzogen\db\BaseDatabaseWriter', $this->writer);
    }

    public function testDatabaseConnectionIsHealthyDueToSqlite()
    {
        $this->assertTrue($this->writer->isHealthy());
    }

    // works on CI, but fails locally due to real config
    public function testDatabaseConnectionIsUnhealthyWithDummyConfiguration()
    {
        $writer = new BaseDatabaseWriter();
        $this->assertFalse($writer->isHealthy());
    }
}
