<?php

use hornherzogen\db\ApplicantStateChanger;
use PHPUnit\Framework\TestCase;

class ApplicantStateChangerTest extends TestCase
{
    private $stateChanger = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->stateChanger = new ApplicantStateChanger();
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        $this->stateChanger = null;
    }

    /**
     * Test instance of.
     *
     * @test
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf('hornherzogen\db\ApplicantStateChanger', $this->stateChanger);
    }

    public function testWithoutConfigEmptyListIsRetrievedWithWeekParameter()
    {
        $this->assertFalse($this->stateChanger->changeStateTo("applicantId", "stateId"));
    }

    public function testMapToSQLWithoutMappableField()
    {
        $this->assertEquals("", $this->stateChanger->mapMappingToSQL(NULL));
        $this->assertEquals("", $this->stateChanger->mapMappingToSQL(array()));
    }

    public function testMapToSQLWithOneMappableField()
    {
        $field = "myFieldInTest";
        $mappingResult = array('field' => $field);
        $this->assertStringStartsWith(" , myFieldInTest = '", $this->stateChanger->mapMappingToSQL($mappingResult));
        // timestamp is in between
        $this->assertStringEndsWith("'", $this->stateChanger->mapMappingToSQL($mappingResult));
    }

    public function testUpdateInDatabaseWithoutDatabaseYieldsNull() {
        $this->assertNull($this->stateChanger->updateInDatabase(4711,4712, NULL));
    }
}
