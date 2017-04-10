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
        $this->assertEmpty($filteredByFlexibility);
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

    public function testGetGroupByCountryWithoutWeek() {
        $this->assertEmpty($this->reader->groupByOriginByWeek(NULL));
    }

    public function testBuildByCountryQueryWithoutWeekParameter() {
        $sql = $this->reader->buildGroupByCountryQuery(NULL);
        $this->assertNotEmpty($sql);
        $this->assertEquals("SELECT a.country, count(*) as ccount FROM `applicants` a GROUP BY a.country", $sql);
    }

    public function testBuildByCountryQueryWithWeekParameter() {
        $week = "MyWeek";
        $sql = $this->reader->buildGroupByCountryQuery($week);
        $this->assertNotEmpty($sql);
        $this->assertEquals("SELECT a.country, count(*) as ccount FROM `applicants` a WHERE a.week LIKE '%MyWeek%' GROUP BY a.country", $sql);
    }

    public function testFoodQueryWithoutWeekParameter() {
        $sql = $this->reader->buildFoodQuery(NULL);
        $this->assertNotEmpty($sql);
        $this->assertEquals("SELECT * from `applicants` a ORDER by a.week, a.essen", $sql);
    }

    public function testFoodQueryWithWeekParameter() {
        $week = "MyWeek";
        $sql = $this->reader->buildFoodQuery($week);
        $this->assertNotEmpty($sql);
        $this->assertEquals("SELECT * from `applicants` a WHERE a.week LIKE '%MyWeek%' ORDER by a.week, a.essen", $sql);
    }
}