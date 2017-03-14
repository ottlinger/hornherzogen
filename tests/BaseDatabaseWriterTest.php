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

    public function testDatabaseConnectionIsHealthyDueToSqlite() {
        $this->assertTrue($this->writer->isHealthy());
    }

    // works on CI, but fails locally due to real config
    public function testDatabaseConnectionIsUnhealthyWithDummyConfiguration() {
        $writer = new BaseDatabaseWriter();
        $this->assertFalse($writer->isHealthy());
    }

    public function testPreventSQLInjectionWithParameterNull() {
        $this->assertNull($this->writer->makeSQLCapable(NULL));
    }

    public function testPreventSQLInjectionWithParameterGiven() {
        $this->assertEquals("'no change needed'", $this->writer->makeSQLCapable("no change needed"));
    }

    public function testPreventSQLInjectionWithSqlInParameterGiven() {
        $this->assertEquals("' \%sdasd \_ff\_'", $this->writer->makeSQLCapable(" %sdasd _ff_"));
    }

    public function testPreventSQLInjectionWithParameterGivenWithoutDatabaseConnection() {
        $writer = new BaseDatabaseWriter();
        $this->assertEquals("'no change needed'", $writer->makeSQLCapable("no change needed"));
    }

    public function testPreventSQLInjectionWithSqlInParameterGivenWithoutDatabaseConnection() {
        $writer = new BaseDatabaseWriter();
        $this->assertEquals("' \%sdasd \_ff\_'", $writer->makeSQLCapable(" %sdasd _ff_"));
    }

}
