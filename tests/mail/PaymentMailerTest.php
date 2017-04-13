<?php
use hornherzogen\ApplicantInput;
use hornherzogen\mail\PaymentMailer;
use PHPUnit\Framework\TestCase;

class PaymentMailerTest extends TestCase
{
    private static $firstname = "Hugo Egon";
    private static $lastname = "Balder";
    private static $remarks = "First line\r\nSecond line\nThird line";
    private $mailer = null;

    /**
     * Setup the test environment and provide an ApplicantInput with all relevant fields set.
     */
    public function setUp()
    {
        // reset language to English
        $_GET = array();
        $_GET['lang'] = "de";

        $this->mailer = new PaymentMailer(self::createApplicantInput());
    }

    private static function createApplicantInput()
    {
        // TODO set all necessary attributes
        $applicantInput = new ApplicantInput();
        $applicantInput->setWeek(1);
        $applicantInput->setFirstname(self::$firstname);
        $applicantInput->setLastname(self::$lastname);
        $applicantInput->setRemarks(self::$remarks);
        $applicantInput->parse();

        return $applicantInput;
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        $this->mailer = null;
    }

    /**
     * Test type of instance of $this->mailer
     *
     * @test
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf('hornherzogen\mail\PaymentMailer', $this->mailer);
    }

}
