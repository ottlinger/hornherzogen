<?php
use hornherzogen\mail\ConfirmationMailer;
use hornherzogen\Applicant;
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

    public function testGermanMailtextIsNotEmpty()
    {
        $applicant = new Applicant();
        $applicant->setPersistenceId(4712);
        $applicant->setLanguage('de');

        $this->assertNotEmpty($this->mailer->getMailtext($applicant));
    }

    public function testEnglishMailtextIsNotEmpty()
    {
        $applicant = new Applicant();
        $applicant->setPersistenceId(4712);
        $applicant->setLanguage('notGerman');

        $this->assertNotEmpty($this->mailer->getMailtext($applicant));
    }

    public function testMailPrefixSetsColour()
    {
        $this->assertContains("green", $this->mailer->getColouredUIPrefix(TRUE));
        $this->assertContains("red", $this->mailer->getColouredUIPrefix(FALSE));
    }

    public function testRoomBookingsInMailWithoutApplicants()
    {
        $this->assertEquals("n/a", $this->mailer->getRoomBookingsInMail(NULL));
    }

    public function testRoomBookingsInMailWithNonGermanSpeakingApplicant()
    {
        $applicant = new Applicant();
        $applicant->setPersistenceId(4712);

        $this->assertEquals("'unknown'", $this->mailer->getRoomBookingsInMail($applicant));
    }

    public function testRoomBookingsInMailWithGermanSpeakingApplicant()
    {
        $applicant = new Applicant();
        $applicant->setPersistenceId(4712);
        $applicant->setLanguage('de');

        $this->assertEquals("'unbekannt'", $this->mailer->getRoomBookingsInMail($applicant));
    }

    public function testRoomBookingsInMailWithAvailableBookings()
    {
        $applicant = new Applicant();
        $applicant->setPersistenceId(-4711);
        $applicant->setLanguage('de');

        $this->assertEquals("<ul><li>My testroom 4711</li><li>My testroom 4712</li></ul>", $this->mailer->getRoomBookingsInMail($applicant));
    }

    public function testConfirmationMailContainsDoNotForgetTWAThingsInEnglish() {
        $applicant = new Applicant();
        $applicant->setLanguage("en");
        $applicant->setTwaNumber("UK-0815");

        $this->assertContains("Please do not forget your twa-passport!", $this->mailer->getEnglishMailtext($applicant));
    }

    public function testConfirmationMailDoesNotContainDoNotForgetTWAThingsInEnglishIfNoTwaMember() {
        $applicant = new Applicant();
        $applicant->setLanguage("en");
        $applicant->setTwaNumber("");

        $this->assertNotContains("Please do not forget your twa-passport!", $this->mailer->getEnglishMailtext($applicant));
    }
}
