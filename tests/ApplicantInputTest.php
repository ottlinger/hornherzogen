<?php
use PHPUnit\Framework\TestCase;
use hornherzogen\ApplicantInput;

class ApplicantInputTest extends TestCase
{
    private $applicantInput = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->applicantInput = new ApplicantInput;
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

    public function testHasNoErrorsWithoutAnyConfiguration()
    {
        $this->assertEmpty($this->applicantInput->showHasError('anythingGoes'));
    }

    public function testHasNoSuccessesWithoutAnyConfiguration()
    {
        $this->assertEmpty($this->applicantInput->showIsOkay('anythingGoes'));
    }

    public function testHasErrorsWithDummyConfiguration()
    {
        $this->applicantInput->addError('name');
        $this->assertContains('error', $this->applicantInput->showHasError('name'));
    }

    public function testHasSuccessWithDummyConfiguration()
    {
        $this->applicantInput->addSuccess('email');
        $this->assertContains('success', $this->applicantInput->showIsOkay('email'));
    }

    public function testEmailIsValid()
    {
        $this->assertTrue(ApplicantInput::isValidEmail('abc@foo.de'));
    }

    public function testEmailIsValidThrowsExceptionIfEmailIsInvalid()
    {
        $this->assertEquals('"abcnodomain" is not a valid email address', ApplicantInput::isValidEmail('abcnodomain'));
    }


}