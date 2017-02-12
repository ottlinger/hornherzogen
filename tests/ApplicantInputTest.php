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

    public function testNoMailaddressesGivenResultsInError() {
        $_POST = array();
        $this->assertFalse($this->applicantInput->areEmailAddressesValid());
    }

    public function testNoMatchingMailaddressesGivenResultsInError() {
        $_POST = array();
        $_POST['email'] = "justATypoe@example.com";
        $_POST['emailcheck'] = "justATypo@example.com";
        $this->assertFalse($this->applicantInput->areEmailAddressesValid());
    }

    public function testMatchingStringsButNoMailaddressesGivenResultsInError() {
        $_POST = array();
        $_POST['email'] = "example.com";
        $_POST['emailcheck'] = "example.com";
        $this->assertFalse($this->applicantInput->areEmailAddressesValid());
    }

    public function testMatchingStringsAndValidMailaddressesGivenWorks() {
        $_POST = array();
        $_POST['email'] = "foo@example.com";
        $_POST['emailcheck'] = "foo@example.com";
        $this->assertTrue($this->applicantInput->areEmailAddressesValid());
    }

}