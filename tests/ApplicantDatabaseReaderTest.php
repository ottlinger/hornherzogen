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

    public function testGetById()
    {
        $this->assertEmpty($this->reader->getById(NULL));
        $this->assertEmpty($this->reader->getById(4711));
    }

    public function testSortedByFlexibility()
    {
        $filteredByFlexibility = $this->reader->listByFlexibilityPerWeek(NULL);
        $this->assertEmpty($filteredByFlexibility);
    }

    public function testBuildQueryWithoutWeekParameter()
    {
        $sql = $this->reader->buildQuery(NULL);
        $this->assertNotEmpty($sql);
        $this->assertEquals("SELECT a.* from `applicants` a ORDER by a.week, a.room", $sql);
    }

    public function testBuildQueryWithWeekParameter()
    {
        $week = "MyWeek";
        $sql = $this->reader->buildQuery($week);
        $this->assertNotEmpty($sql);
        $this->assertEquals("SELECT a.* from `applicants` a WHERE a.week LIKE '%MyWeek%' ORDER by a.week, a.room", $sql);
    }

    public function testGetGroupByCountryWithoutWeek()
    {
        $this->assertEmpty($this->reader->groupByOriginByWeek(NULL));
    }

    public function testBuildByCountryQueryWithoutWeekParameter()
    {
        $sql = $this->reader->buildGroupByCountryQuery(NULL);
        $this->assertNotEmpty($sql);
        $this->assertEquals("SELECT a.country, count(*) as ccount FROM `applicants` a GROUP BY a.country", $sql);
    }

    public function testBuildByCountryQueryWithWeekParameter()
    {
        $week = "MyWeek";
        $sql = $this->reader->buildGroupByCountryQuery($week);
        $this->assertNotEmpty($sql);
        $this->assertEquals("SELECT a.country, count(*) as ccount FROM `applicants` a WHERE a.week LIKE '%MyWeek%' GROUP BY a.country", $sql);
    }

    public function testFoodQueryWithoutWeekParameter()
    {
        $sql = $this->reader->buildFoodQuery(NULL);
        $this->assertNotEmpty($sql);
        $this->assertEquals("SELECT a.* from `applicants` a ORDER by a.week, a.essen", $sql);
    }

    public function testFoodQueryWithWeekParameter()
    {
        $week = "MyWeek";
        $sql = $this->reader->buildFoodQuery($week);
        $this->assertNotEmpty($sql);
        $this->assertEquals("SELECT a.* from `applicants` a WHERE a.week LIKE '%MyWeek%' ORDER by a.week, a.essen", $sql);
    }

    public function testFlexibilityQueryWithoutWeekParameter()
    {
        $sql = $this->reader->buildFlexibilityQuery(NULL);
        $this->assertNotEmpty($sql);
        $this->assertEquals("SELECT a.* from `applicants` a WHERE flexible in ('yes', '1')", trim($sql));
    }

    public function testFlexibilityQueryWithWeekParameter()
    {
        $week = "MyWeek";
        $sql = $this->reader->buildFlexibilityQuery($week);
        $this->assertNotEmpty($sql);
        $this->assertEquals("SELECT a.* from `applicants` a WHERE flexible in ('yes', '1')  AND a.week LIKE '%MyWeek%'", $sql);
    }

    public function testWithoutConfigEmptyListIsRetrievedWithoutWeekParameter()
    {
        // may return results locally :-D
        $this->assertEquals(0, sizeof($this->reader->getAllByWeek()));
    }

    public function testWithoutConfigEmptyListIsRetrievedWithWeekParameter()
    {
        $this->assertEquals(0, sizeof($this->reader->getAllByWeek("week1")));
    }

    public function testGetAllQueryWithoutWeekParameter()
    {
        $sql = $this->reader->buildGetAllQuery(NULL);
        $this->assertNotEmpty($sql);
        $this->assertEquals("SELECT a.* from `applicants` a ORDER BY a.created", trim($sql));
    }

    public function testGetAllQueryWithWeekParameter()
    {
        $week = "MyWeek";
        $sql = $this->reader->buildGetAllQuery($week);
        $this->assertNotEmpty($sql);
        $this->assertEquals("SELECT a.* from `applicants` a WHERE a.week LIKE '%MyWeek%' ORDER BY a.created", $sql);
    }

    public function testPaidButNotConfirmedQueryWithoutWeek()
    {
        $sql = $this->reader->buildPaidButNotConfirmedQuery(NULL);
        $this->assertNotEmpty($sql);
        $this->assertEquals("SELECT a.* from `applicants` a, status s WHERE s.name='PAID' AND a.statusId = s.id ORDER BY a.created LIMIT 50", trim($sql));
    }

    public function testPaidButNotConfirmedQueryWithWeek()
    {
        $week = "MyWeek";
        $sql = $this->reader->buildPaidButNotConfirmedQuery($week);
        $this->assertNotEmpty($sql);
        $this->assertEquals("SELECT a.* from `applicants` a, status s WHERE s.name='PAID' AND a.statusId = s.id AND a.week LIKE '%MyWeek%' ORDER BY a.created LIMIT 50", $sql);
    }

    public function testGetPaidButNotConfirmedApplicants()
    {
        $this->assertEmpty($this->reader->getPaidButNotConfirmedApplicants());
    }

    public function testGetOverduePayments() {
        $this->assertEmpty($this->reader->getOverduePayments());
    }

    public function testOverduePaymentQueryWithoutWeek() {
        $sql = $this->reader->buildOverduePaymentQuery(NULL);
        $this->assertNotEmpty($sql);
        $this->assertEquals("SELECT a.* from `applicants` a WHERE now() >= DATE_ADD(a.paymentmailed, INTERVAL 2 WEEK) AND a.paymentreceived IS NULL AND statusId NOT IN (select id from status where name in ('PAID','BOOKED','CANCELLED')) ORDER BY a.paymentmailed ASC", trim($sql));
    }

    public function testOverduePaymentQueryWithWeek() {
        $week = "AWeek";
        $sql = $this->reader->buildOverduePaymentQuery($week);
        $this->assertNotEmpty($sql);
        $this->assertEquals("SELECT a.* from `applicants` a WHERE now() >= DATE_ADD(a.paymentmailed, INTERVAL 2 WEEK) AND a.week LIKE '%AWeek%' AND a.paymentreceived IS NULL AND statusId NOT IN (select id from status where name in ('PAID','BOOKED','CANCELLED')) ORDER BY a.paymentmailed ASC", trim($sql));
    }

}