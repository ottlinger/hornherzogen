<?php
use PHPUnit\Framework\TestCase;

class SubmitMailerTest extends TestCase
{
    private $mailer = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->mailer = new hornherzogen\SubmitMailer;
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
        $this->assertInstanceOf('hornherzogen\SubmitMailer', $this->mailer);
    }

    /**
     * Test internal mail submission.
     *
     * @test
     */
    public function testValidInternalMailSendingWhenConfigIsSetProperly()
    {
        $GLOBALS["horncfg"]["sendinternalregistrationmails"] = true;
        $this->assertEquals("An internal confirmation mail needs to be sent as well :-)", $this->mailer->sendInternally());
    }

    /**
     * Test internal mail submission is disabled if configured in that way.
     *
     * @test
     */
    public function testInternalMailsAreNotSendIfNotConfigured()
    {
        $GLOBALS["horncfg"]["sendinternalregistrationmails"] = false;
        $this->assertEquals('', $this->mailer->sendInternally());
    }

    /**
     * Test mail submission fails if no email is set.
     *
     * @test
     */
    public function testNoMailIsSendWithoutInputData()
    {
        $this->assertEquals('<p>Invalid emailadress - no mail to send</p>', $this->mailer->send());
    }

    public function testNoMailIsSendifConfiguredThisWay()
    {
        // do not send any mails in tests
        $GLOBALS["horncfg"]["sendregistrationmails"] = false;
        $GLOBALS["horncfg"]["sendinternalregistrationmails"] = false;

        // Faked correct $_POST - will disappear if extracted properly
        $_POST['email'] = 'admin@foo.bar';
        $_POST['emailcheck'] = 'admin@foo.bar';
        $_SERVER["REMOTE_ADDR"] = '127.0.0.1';
        $_SERVER["SERVER_NAME"] = 'justATest.local';

        $this->assertStringStartsWith('<p>Mail abgeschickt um ', $this->mailer->send());
        $this->assertFalse($this->mailer->sendInternally());
    }

}