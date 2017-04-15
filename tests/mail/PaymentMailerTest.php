<?php
use hornherzogen\mail\PaymentMailer;
use PHPUnit\Framework\TestCase;

class PaymentMailerTest extends TestCase
{
    private $mailer = null;

    /**
     * Setup the test environment and provide an ApplicantInput with all relevant fields set.
     */
    public function setUp()
    {
        // reset language to English
        $_GET = array();
        $_GET['lang'] = "de";

        $this->mailer = new PaymentMailer(PaymentMailer::TEST_APPLICANT_ID);
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
