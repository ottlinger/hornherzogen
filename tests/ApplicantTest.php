<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use hornherzogen\Applicant;

class ApplicantTest extends TestCase
{
    private $applicant = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->applicant = new Applicant();
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        $this->applicant = null;
    }

    /**
     * Test instance of.
     *
     * @test
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf('hornherzogen\Applicant', $this->applicant);
    }

    public function testAttributePersistenceId() {
        $persistenceId = 47110815;
        $this->applicant->setPersistenceId($persistenceId);
        $this->assertEquals($persistenceId, $this->applicant->getPersistenceId());
    }

    public function testAttributeWeek() {
        $week = "week4711";
        $this->applicant->setWeek($week);
        $this->assertEquals($week, $this->applicant->getWeek());
    }

    public function testAttributeWeekParsingOfFormValuesWeekOne() {
        $week = "week1";
        $this->applicant->setWeek($week);
        $this->assertEquals(1, $this->applicant->getWeek());
    }

    public function testAttributeWeekParsingOfFormValuesWeekTwo() {
        $week = "week2";
        $this->applicant->setWeek($week);
        $this->assertEquals(2, $this->applicant->getWeek());
    }
}
