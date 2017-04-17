<?php

use hornherzogen\db\ApplicantDataSplitter;
use PHPUnit\Framework\TestCase;

class ApplicantDataSplitterTest extends TestCase
{
    private $stateChanger = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->stateChanger = new ApplicantDataSplitter();
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
        $this->assertInstanceOf('hornherzogen\db\ApplicantDataSplitter', $this->stateChanger);
    }

    public function testRetrievalWithNoDatabaseResults()
    {
        $this->assertCount(4, $this->stateChanger->splitByRoomCategory(NULL));
    }

    public function testRetrievalWithDatabaseResults3Bed()
    {
        $dbResult = array();
        $dbResult[0] = array('room' => '3bed');

        $this->assertCount(4, $this->stateChanger->splitByRoomCategory($dbResult));
        $this->assertCount(1, $this->stateChanger->splitByRoomCategory($dbResult)[3]);
    }

    public function testRetrievalWithDatabaseResults2Bed()
    {
        $dbResult = array();
        $dbResult[0] = array('room' => '2bed');

        $this->assertCount(4, $this->stateChanger->splitByRoomCategory($dbResult));
        $this->assertCount(1, $this->stateChanger->splitByRoomCategory($dbResult)[2]);
    }

}
