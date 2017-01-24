<?php

class SubmitMailerTest extends PHPUnit_Framework_TestCase
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
    public function testValidInternalMailSending()
    {
        $this->assertEquals('Not yet implemented', $this->mailer->sendInternally());
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


}