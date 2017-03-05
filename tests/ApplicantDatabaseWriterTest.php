<?php
use hornherzogen\db\ApplicantDatabaseWriter;
use PHPUnit\Framework\TestCase;


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

    public function testWithoutConfigEmptyListIsRetrievedWithoutWeekParameter()
    {
        // may return results locally :-D
        $this->assertEquals(0, sizeof($this->writer->getAllByWeek()));
    }

    public function testWithoutConfigEmptyListIsRetrievedWithWeekParameter()
    {
        $this->assertEquals(0, sizeof($this->writer->getAllByWeek("week1")));
    }

}