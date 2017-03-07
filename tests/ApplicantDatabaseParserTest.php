<?php
use PHPUnit\Framework\TestCase;
use hornherzogen\db\ApplicantDatabaseParser;

class ApplicantDatabaseParserTest extends TestCase
{
    private $writer = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->writer = new ApplicantDatabaseParser();
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
        $this->assertInstanceOf('hornherzogen\db\ApplicantDatabaseParser', $this->writer);
    }

    public function testEmptyToNullWithNullArgument() {
        $this->assertNull($this->writer->emptyToNull(NULL));
    }

    public function testEmptyToNullWithNonNullArgument() {
        $this->assertEquals("asd dsa", $this->writer->emptyToNull("  asd dsa "));
    }

}
