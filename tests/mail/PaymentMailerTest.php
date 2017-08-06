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
        $_GET = [];
        $_GET['lang'] = 'de';

        // prevent sending mails in tests
        $GLOBALS['horncfg']['sendregistrationmails'] = false;
        $GLOBALS['horncfg']['sendinternalregistrationmails'] = false;

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
     * Test type of instance of $this->mailer.
     *
     * @test
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf('hornherzogen\mail\PaymentMailer', $this->mailer);
    }

    public function testGermanMailTextContainsAllAccountInformation()
    {
        $text = $this->mailer->getMailtext();
        $this->assertContains('Emil Mustermann', $text);
        $this->assertContains('C`mor Butts', $text);
        $this->assertContains('DEWOOO', $text);
        $this->assertContains('BICTOR', $text);
        $this->assertContains('250,00 €', $text);
        $this->assertContains('Woche 2', $text);
    }

    public function testEnglishMailTextContainsAllAccountInformation()
    {
        $text = $this->mailer->getEnglishMailtext();
        $this->assertContains('Emil Mustermann', $text);
        $this->assertContains('C`mor Butts', $text);
        $this->assertContains('DEWOOO', $text);
        $this->assertContains('BICTOR', $text);
        $this->assertContains('250,00 €', $text);
        $this->assertContains('week 2', $text);
    }

    public function testPriceCalculationWithTWA()
    {
        $this->assertEquals('250,00 €', $this->mailer->getSeminarPrice());
    }

    public function testPriceCalculationWithoutTWA()
    {
        $mailer = new PaymentMailer(null);
        $this->assertEquals('300,00 €', $mailer->getSeminarPrice());
    }

    public function testSendWithoutApplicant()
    {
        $mailer = new PaymentMailer(null);
        $this->assertEquals('Nothing to send.', $mailer->send());
    }

    public function testSendInternallyWithoutApplicant()
    {
        $mailer = new PaymentMailer(null);
        $this->assertEquals('Nothing to send internally.', $mailer->sendInternally());
    }

    public function testSend()
    {
        $this->assertEquals('', $this->mailer->send());
    }

    public function testSendInternally()
    {
        $this->assertEquals('', $this->mailer->sendInternally());
    }

    public function testGetMailSubjectDirectlyAsItIsInLanguageOfApplicantAndNotCurrentSession()
    {
        $withParam = new MessageFormatter('de', $GLOBALS['messages']['de']['PMAIL.SUBJECT']);
        $subject = $withParam->format(['TimeStamp']);
        $this->assertContains('Anmeldebestätigung', $subject);
        $this->assertContains('TimeStamp', $subject);
    }

    public function testGetInternalMailText()
    {
        $mailtext = $this->mailer->getInternalMailtext();
        $this->assertContains('Emil Musterman', $mailtext);
        $this->assertContains('Zahlungsbestätigung', $mailtext);
        $this->assertContains('Woche: 2', $mailtext);
        $this->assertContains('Umbuchbar? nein', $mailtext);
        $this->assertContains('Frau', $mailtext);
        $this->assertContains('250,00 €', $mailtext);
        $this->assertContains('CC-0815', $mailtext);
    }
}
