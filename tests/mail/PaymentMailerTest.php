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

    public function testGermanMailTextContainsAllAccountInformation()
    {
        $text = $this->mailer->getMailtext();
        $this->assertContains("Emil Mustermann", $text);
        $this->assertContains("C`mor Butts", $text);
        $this->assertContains("DEWOOO", $text);
        $this->assertContains("BICTOR", $text);
        $this->assertContains("250,00 €", $text);
        $this->assertContains("Woche 2", $text);
    }

    public function testEnglishMailTextContainsAllAccountInformation()
    {
        $this->assertEquals("", $this->mailer->getEnglishMailtext());
    }

    public function testPriceCalculationWithTWA()
    {
        $this->assertEquals("250,00 €", $this->mailer->getSeminarPrice());
    }

    public function testPriceCalculationWithoutTWA()
    {
        $mailer = new PaymentMailer(NULL);
        $this->assertEquals("300,00 €", $mailer->getSeminarPrice());
    }

}
