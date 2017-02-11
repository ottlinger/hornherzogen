<?php
declare(strict_types = 1);
use hornherzogen\Applicant;
use PHPUnit\Framework\TestCase;

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

    public function testAttributeWeek()
    {
        $week = "week4711";
        $this->applicant->setWeek($week);
        $this->assertEquals($week, $this->applicant->getWeek());
    }

    public function testAttributeWeekParsingOfFormValuesWeekOne()
    {
        $week = "week1";
        $this->applicant->setWeek($week);
        $this->assertEquals(1, $this->applicant->getWeek());
    }

    public function testAttributeWeekParsingOfFormValuesWeekTwo()
    {
        $week = "week2";
        $this->applicant->setWeek($week);
        $this->assertEquals(2, $this->applicant->getWeek());
    }

    public function testAttributeFirstName()
    {
        $firstName = "Karl-Theodor Maria Nikolaus Johann Jacob Philipp Franz Joseph Sylvester";
        $this->applicant->setFirstname($firstName);
        $this->assertEquals($firstName, $this->applicant->getFirstname());
    }

    public function testAttributeLastName()
    {
        $lastName = "Buhl-Freiherr von und zu Guttenberg";
        $this->applicant->setLastname($lastName);
        $this->assertEquals($lastName, $this->applicant->getLastname());
    }

    public function testAttributeFullNameWithoutAdditionalStuff()
    {
        $lastName = "Buhl-Freiherr von und zu Guttenberg";
        $firstName = "Karl-Theodor Maria Nikolaus Johann Jacob Philipp Franz Joseph Sylvester";
        $this->applicant->setFirstname($firstName)->setLastname($lastName);
        $this->assertEquals(trim($firstName . ' ' . $lastName), $this->applicant->getFullname());

        // with salt
        $salt = "the third";
        $this->applicant->setFullName($salt);
        $this->assertEquals($firstName . ' ' . $lastName . ' ' . $salt, $this->applicant->getFullname());
    }

    /*
        private $street;
        private $houseNumber;
        private $zipCode;
        private $city;
        private $country;
        private $email;
        private $dojo;
        private $dateOfBirth;
        private $grading;
        private $dateOfLastGrading;
        private $twaNumber;

        private $room; // which kind of room
        private $partnerOne;
        private $partnerTwo;

        private $foodCategory;
    */

    public function testAttributeFlexibleWithParsingToBoolean()
    {
        $flexible = "Karl-Theodor Maria Nikolaus Johann Jacob Philipp Franz Joseph Sylvester";
        $this->applicant->setFlexible($flexible);
        $this->assertFalse($this->applicant->getFlexible());


        $this->applicant->setFlexible("yes");
        $this->assertTrue($this->applicant->getFlexible());
    }

    public function testAttributeRemarks()
    {
        $remark = "AnyAdditonalComments";
        $this->applicant->setRemarks($remark);
        $this->assertEquals($remark, $this->applicant->getRemarks());
    }

    public function testAttributeRemarksLengthConstraint()
    {
        $remark = str_repeat("a", 400);
        $this->applicant->setRemarks($remark . "ThisIsTrimmed");
        $this->assertEquals($remark, $this->applicant->getRemarks());
    }

    public function testAttributeRemarksWithNullParameter()
    {
        $this->applicant->setRemarks(NULL);
        $this->assertNull($this->applicant->getRemarks());
    }
    /*
        // end of form data

        // DB-specific stuff
    */
    public function testAttributePersistenceId()
    {
        $persistenceId = 47110815;
        $this->applicant->setPersistenceId($persistenceId);
        $this->assertEquals($persistenceId, $this->applicant->getPersistenceId());
    }

    /*
        // Admin-related stuff
        private $createdAt; // date when status was set to APPLIED
        private $confirmedAt; // date when status was set to CONFIRMED
        private $finalRoom; // reference to other table, final room at Herzogenhorn
        private $currentStatus; // name in other table
        private $mailedAt; // when the mail was sent and status changed to REGISTERED
        private $paymentRequestedAt; // when the mail to pay was sent and status changed to WAITING_FOR_PAYMENT
        private $paymentReceivedAt; // when the payment was received successfully and status changed to PAID
        private $bookedAt;// final confirmation is sent out and status changed to BOOKED
    */
}
