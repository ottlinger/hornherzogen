<?php
use hornherzogen\mail\ConfirmationMailer;
use PHPUnit\Framework\TestCase;

class ConfirmationMailerTest extends TestCase
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

        // prevent sending mails in tests
        $GLOBALS['horncfg']['sendregistrationmails'] = false;
        $GLOBALS['horncfg']['sendinternalregistrationmails'] = false;

        $this->mailer = new ConfirmationMailer(ConfirmationMailer::TEST_APPLICANT);
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
        $this->assertInstanceOf('hornherzogen\mail\ConfirmationMailer', $this->mailer);
    }

    public function testConfirmDummyApplicantSending()
    {
        $this->mailer = new ConfirmationMailer(NULL);
        $this->assertEquals(1, $this->mailer->sendAsBatch());
    }

    public function testMailSendingWithoutAnyApplicants()
    {
        $this->mailer = new ConfirmationMailer(array());
        $this->assertEquals(-1, $this->mailer->sendAsBatch());
    }

    public function testSendNullArgument()
    {
        $this->assertEquals("Nothing to send.", $this->mailer->send(NULL));
    }

}
