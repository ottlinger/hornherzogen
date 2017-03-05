<?php
use PHPUnit\Framework\TestCase;
use hornherzogen\db\BaseDatabaseWriter;

class BaseDatabaseWriterTest extends TestCase
{
    private $writer = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->writer = new BaseDatabaseWriter();
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

    public function testDatabaseConnectionIsHealthyLocally() {
        $this->assertFalse($this->writer->isHealthy());
    }

}
