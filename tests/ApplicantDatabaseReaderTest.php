<?php

use hornherzogen\db\ApplicantDatabaseReader;
use PHPUnit\Framework\TestCase;

class ApplicantDatabaseReaderTest extends TestCase
{
    private $reader = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->reader = new ApplicantDatabaseReader();
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        $this->reader = null;
    }

    /**
     * Test instance of.
     *
     * @test
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf('hornherzogen\db\ApplicantDatabaseReader', $this->reader);
    }

    public function testRoomWishesResultStructure()
    {
        $this->assertEquals(4, sizeof($this->reader->listByRoomCategoryPerWeek(NULL)));
    }

    public function testFoodReservations()
    {
        $this->assertEmpty($this->reader->listByFoodCategoryPerWeek(NULL));
    }

    public function testGetById() {
        $this->assertEmpty($this->reader->getById(NULL));
        $this->assertEmpty($this->reader->getById(4711));
    }

    public function testSortedByFlexibility() {
        $filteredByFlexibility = $this->reader->listByFlexibilityPerWeek(NULL);
        $this->assertEquals(2, sizeof($filteredByFlexibility));
        $this->assertEmpty($filteredByFlexibility['flexible']);
        $this->assertEmpty($filteredByFlexibility['static']);
    }

    public function testBuildQueryWithoutWeekParameter() {
        $sql = $this->reader->buildQuery(NULL);
        $this->assertNotEmpty($sql);
        $this->assertEquals("SELECT * from `applicants` a ORDER by a.week, a.room", $sql);
    }

    public function testBuildQueryWithWeekParameter() {
        $week = "MyWeek";
        $sql = $this->reader->buildQuery($week);
        $this->assertNotEmpty($sql);
        $this->assertEquals("SELECT * from `applicants` a WHERE a.week LIKE '%MyWeek%' ORDER by a.week, a.room", $sql);
    }

}