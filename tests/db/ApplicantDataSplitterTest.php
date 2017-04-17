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

    public function testRetrievalWithNoDatabaseResults() {
        $this->assertCount(4, $this->stateChanger->splitByRoomCategory(NULL));
    }

}
