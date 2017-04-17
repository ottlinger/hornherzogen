<?php

use hornherzogen\db\ApplicantDataSplitter;
use PHPUnit\Framework\TestCase;
use hornherzogen\Applicant;

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

    public function testRetrievalWithDatabaseResults1Bed()
    {
        $dbResult = array();
        $dbResult[0] = array('room' => '1bed');

        $this->assertCount(4, $this->stateChanger->splitByRoomCategory($dbResult));
        $this->assertCount(1, $this->stateChanger->splitByRoomCategory($dbResult)[1]);
    }

    public function testRetrievalWithDatabaseResultsArbitraryCategory()
    {
        $dbResult = array();
        $dbResult[0] = array('room' => 'anythingGoesHere');

        $this->assertCount(4, $this->stateChanger->splitByRoomCategory($dbResult));
        $this->assertCount(1, $this->stateChanger->splitByRoomCategory($dbResult)[4]);
    }

    public function testSplitByGenderWithNoInputGiven()
    {
        $applicants = array();
        $splitted = $this->stateChanger->splitByGender($applicants);
        $this->assertNotNull($splitted);
        $this->assertEquals(3, sizeof($splitted));
        $this->assertEmpty($splitted['male']);
        $this->assertEmpty($splitted['female']);
        $this->assertEmpty($splitted['other']);
    }

    public function testSplitByGenderWithValidInput()
    {
        $male = new Applicant();
        $male->setGender('male');
        $female = new Applicant();
        $female->setGender('female');
        $other = new Applicant();
        $other->setGender('other');
        $applicants = array();
        $applicants[] = $male;
        $applicants[] = $female;
        $applicants[] = $other;

        $splitted = $this->stateChanger->splitByGender($applicants);
        $this->assertNotNull($splitted);
        $this->assertEquals(3, sizeof($splitted));
        $this->assertContainsOnly($male, $splitted['male']);
        $this->assertContainsOnly($female, $splitted['female']);
        $this->assertContainsOnly($other, $splitted['other']);
    }

}
