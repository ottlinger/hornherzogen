<?php
use PHPUnit\Framework\TestCase;
use hornherzogen\db\ApplicantDatabaseWriter;


class ApplicantDatabaseWriterTest extends TestCase
{
    private $writer = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->writer = new ApplicantDatabaseWriter();
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
        $this->assertInstanceOf('hornherzogen\db\ApplicantDatabaseWriter', $this->writer);
    }

}