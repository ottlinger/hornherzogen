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

    public function mapToSQLWithoutMappableField() {
        $this->assertEquals("", $this->stateChanger->mapMappingResult(NULL));
        $this->assertEquals("", $this->stateChanger->mapMappingResult(array()));
    }

}
