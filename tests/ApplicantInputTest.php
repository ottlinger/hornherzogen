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
        $this->assertEquals('', $this->applicantInput->showHasError('unknownKey'));
    }

    public function testHasSuccessWithDummyConfiguration()
    {
        $this->applicantInput->addSuccess('email');
        $this->assertContains('success', $this->applicantInput->showIsOkay('email'));
        $this->assertEquals('', $this->applicantInput->showIsOkay('unknownKey'));
    }

    public function testParseFromUserInputWithoutAnyUserInputGiven()
    {
        $this->applicantInput->parse();
        $this->assertEmpty($this->applicantInput->getFirstName());
    }

    public function testParseFromUserInputWitUserInputGiven()
    {
        $_POST["vorname"] = "  <b>My firstname</b> ";
        $this->applicantInput->parse();
        $this->assertEquals("&lt;b&gt;My firstname&lt;/b&gt;", $this->applicantInput->getFirstName());
    }

    public function testNumberOfFieldsRequiredInWebFormDidChange() {
        $this->assertCount(17, ApplicantInput::getRequiredFields());
    }

}