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

    public function testAttributeStreet()
    {
        $data = "Up de straat";
        $this->applicant->setStreet($data);
        $this->assertEquals($data, $this->applicant->getStreet());
    }

    public function testAttributeHouseNumber()
    {
        $data = "654a";
        $this->applicant->setHouseNumber($data);
        $this->assertEquals($data, $this->applicant->getHouseNumber());
    }

    public function testAttributeZipCode()
    {
        $data = "04600";
        $this->applicant->setZipCode($data);
        $this->assertEquals($data, $this->applicant->getZipCode());
    }

    public function testAttributeCity()
    {
        $data = "東京";
        $this->applicant->setCity($data);
        $this->assertEquals($data, $this->applicant->getCity());
    }

    public function testAttributeCountry()
    {
        $data = "Wonderland";
        $this->applicant->setCountry($data);
        $this->assertEquals($data, $this->applicant->getCountry());
    }

    public function testAttributeEmail()
    {
        $data = "winne@example.com";
        $this->applicant->setEmail($data);
        $this->assertEquals($data, $this->applicant->getEmail());
    }

    public function testAttributeRoom()
    {
        $data = "Suite with pool";
        $this->applicant->setRoom($data);
        $this->assertEquals($data, $this->applicant->getRoom());
    }

    public function testAttributeDojo()
    {
        $data = "KaiShinKanInHeaven";
        $this->applicant->setDojo($data);
        $this->assertEquals($data, $this->applicant->getDojo());
    }

    public function testAttributeGrading()
    {
        $data = $this->currentTimestamp();
        $this->applicant->setGrading($data);
        $this->assertEquals($data, $this->applicant->getGrading());
    }

    public function currentTimestamp()
    {
        return date('Y-m-d H:i:s');
    }

    public function testAttributeDateOfLastGrading()
    {
        $data = $this->currentTimestamp();
        $this->applicant->setDateOfLastGrading($data);
        $this->assertEquals($data, $this->applicant->getDateOfLastGrading());
    }

    public function testAttributeTwaNumber()
    {
        $data = "DE-0815";
        $this->applicant->setTwaNumber($data);
        $this->assertEquals($data, $this->applicant->getTwaNumber());
    }

    public function testAttributePartnerOne()
    {
        $data = "Uke1";
        $this->applicant->setPartnerOne($data);
        $this->assertEquals($data, $this->applicant->getPartnerOne());
    }

    public function testAttributePartnerTwo()
    {
        $data = "Uke2";
        $this->applicant->setPartnerTwo($data);
        $this->assertEquals($data, $this->applicant->getPartnerTwo());
    }

    public function testFoodCategory()
    {
        $data = "veg";
        $this->applicant->setFoodCategory($data);
        $this->assertEquals($data, $this->applicant->getFoodCategory());
    }

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
    // end of form data

    // DB-specific stuff

    public function testAttributeRemarksWithNullParameter()
    {
        $this->applicant->setRemarks(NULL);
        $this->assertNull($this->applicant->getRemarks());
    }

    // Admin-related stuff

    public function testAttributePersistenceId()
    {
        $persistenceId = 47110815;
        $this->applicant->setPersistenceId($persistenceId);
        $this->assertEquals($persistenceId, $this->applicant->getPersistenceId());
    }

    public function testAttributeCreatedAt()
    {
        $data = $this->currentTimestamp();
        $this->applicant->setCreatedAt($data);
        $this->assertEquals($data, $this->applicant->getCreatedAt());
    }

    public function testAttributeConfirmedAt()
    {
        $data = $this->currentTimestamp();
        $this->applicant->setConfirmedAt($data);
        $this->assertEquals($data, $this->applicant->getConfirmedAt());
    }

    public function testFinalRoom()
    {
        $data = "0815";
        $this->applicant->setFinalRoom($data);
        $this->assertEquals($data, $this->applicant->getFinalRoom());
    }

    public function testCurrentStatus()
    {
        $data = "ASSIMILATED";
        $this->applicant->setCurrentStatus($data);
        $this->assertEquals($data, $this->applicant->getCurrentStatus());
    }

    public function testAttributeMailedAt()
    {
        $data = $this->currentTimestamp();
        $this->applicant->setMailedAt($data);
        $this->assertEquals($data, $this->applicant->getMailedAt());
    }

    public function testAttributePaymentRequestedAt()
    {
        $data = $this->currentTimestamp();
        $this->applicant->setPaymentRequestedAt($data);
        $this->assertEquals($data, $this->applicant->getPaymentRequestedAt());
    }

    public function testAttributePaymentReceivedAt()
    {
        $data = $this->currentTimestamp();
        $this->applicant->setPaymentReceivedAt($data);
        $this->assertEquals($data, $this->applicant->getPaymentReceivedAt());
    }

    public function testAttributeBookedAt()
    {
        $data = $this->currentTimestamp();
        $this->applicant->setBookedAt($data);
        $this->assertEquals($data, $this->applicant->getBookedAt());
    }
}
