<?php
use PHPUnit\Framework\TestCase;

class ApplicantInputTest extends TestCase
{
    private $applicantInput = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->applicantInput = new hornherzogen\ApplicantInput;
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        $this->applicantInput = null;
    }

    /**
     * Test instance of.
     *
     * @test
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf('hornherzogen\ApplicantInput', $this->applicantInput);
    }

    /**
     * Return trimmed mail value from config.
     *
     * @test
     */
    public function testIsAlwaysErrorSinceNoSuccess()
    {
        $this->assertTrue($this->applicantInput->hasErrors());
    }


}