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

    public function testAttributeFirstName() {
        $firstName = "Karl-Theodor Maria Nikolaus Johann Jacob Philipp Franz Joseph Sylvester";
        $this->applicant->setFirstname($firstName);
        $this->assertEquals($firstName, $this->applicant->getFirstname());
    }

    public function testAttributeLastName() {
        $lastName = "Buhl-Freiherr von und zu Guttenberg";
        $this->applicant->setLastname($lastName);
        $this->assertEquals($lastName, $this->applicant->getLastname());
    }

    public function testAttributeFullNameWithoutAdditionalStuff() {
        $lastName = "Buhl-Freiherr von und zu Guttenberg";
        $firstName = "Karl-Theodor Maria Nikolaus Johann Jacob Philipp Franz Joseph Sylvester";
        $this->applicant->setFirstname($firstName)->setLastname($lastName);
        $this->assertEquals(trim($firstName.' '.$lastName), $this->applicant->getFullname());

        // with salt
        $salt = "the third";
        $this->applicant->setFullName($salt);
        $this->assertEquals($firstName.' '.$lastName.' '.$salt, $this->applicant->getFullname());
    }





}
