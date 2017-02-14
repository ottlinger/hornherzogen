<?php
use hornherzogen\ApplicantInput;
use PHPUnit\Framework\TestCase;

class ApplicantInputTest extends TestCase
{
    private $applicantInput = null;

    private static function prepareForSuccessParsing($field)
    {
        $_POST = array();
        $_POST[$field] = $field;
    }

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
        $this->assertEmpty($this->applicantInput->showIsSuccess('anythingGoes'));
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
        $this->assertContains('success', $this->applicantInput->showIsSuccess('email'));
        $this->assertEquals('', $this->applicantInput->showIsSuccess('unknownKey'));
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

    public function testNumberOfFieldsRequiredInWebFormDidChange()
    {
        $this->assertCount(17, ApplicantInput::getRequiredFields());
    }

    public function testNoMailaddressesGivenResultsInError()
    {
        $_POST = array();
        $this->assertFalse($this->applicantInput->areEmailAddressesValid());
    }

    public function testNoMatchingMailaddressesGivenResultsInError()
    {
        $_POST = array();
        $_POST['email'] = "justATypoe@example.com";
        $_POST['emailcheck'] = "justATypo@example.com";
        $this->assertFalse($this->applicantInput->areEmailAddressesValid());
    }

    public function testMatchingStringsButNoMailaddressesGivenResultsInError()
    {
        $_POST = array();
        $_POST['email'] = "example.com";
        $_POST['emailcheck'] = "example.com";
        $this->assertFalse($this->applicantInput->areEmailAddressesValid());
    }

    public function testMatchingStringsAndValidMailaddressesGivenWorks()
    {
        $_POST = array();
        $_POST['email'] = "foo@example.com";
        $_POST['emailcheck'] = "foo@example.com";
        $this->assertTrue($this->applicantInput->areEmailAddressesValid());
    }

    public function testToStringAfterInit()
    {
        $_POST = array();
        $this->applicantInput->parse();
        $toString = $this->applicantInput->__toString();

        $this->assertContains("ERROR", $toString);
        $this->assertContains("SUCCESS", $toString);
        $this->assertContains("WITH A TOTAL OF", $toString);
        $this->assertContains("hasErrors? 1", $toString);
    }

    public function testErroneousParsingOfFieldWeek()
    {
        $field = "week";
        self::prepareForErrorParsing($field);
        $this->applicantInput->parse();
        $this->assertNotEmpty($this->applicantInput->showHasError($field));
    }

    private static function prepareForErrorParsing($field)
    {
        $_POST = array();
        $_POST[$field] = [];
    }

    public function testSuccessfulParsingOfFieldWeek()
    {
        $field = "week";
        self::prepareForSuccessfulParsing($field);
        $this->applicantInput->parse();
        $this->assertEquals('', $this->applicantInput->showHasError($field));
        $this->assertNotEmpty($this->applicantInput->showIsSuccess($field));
    }

    private static function prepareForSuccessfulParsing($field)
    {
        $_POST = array();
        $_POST[$field] = $field;
    }

    public function testErroneousParsingOfFieldFlexible()
    {
        $field = "flexible";
        self::prepareForErrorParsing($field);
        $this->applicantInput->parse();
        $this->assertNotEmpty($this->applicantInput->showHasError($field));
    }

    public function testSuccessfulParsingOfFieldFlexible()
    {
        $field = "flexible";
        self::prepareForSuccessfulParsing($field);
        $this->applicantInput->parse();
        $this->assertEquals('', $this->applicantInput->showHasError($field));
        $this->assertNotEmpty($this->applicantInput->showIsSuccess($field));
    }

    public function testErroneousParsingOfFieldGender()
    {
        $field = "gender";
        self::prepareForErrorParsing($field);
        $this->applicantInput->parse();
        $this->assertNotEmpty($this->applicantInput->showHasError($field));
    }

    public function testSuccessfulParsingOfFieldGender()
    {
        $field = "gender";
        self::prepareForSuccessfulParsing($field);
        $this->applicantInput->parse();
        $this->assertEquals('', $this->applicantInput->showHasError($field));
        $this->assertNotEmpty($this->applicantInput->showIsSuccess($field));
    }

    public function testErroneousParsingOfFieldLastname()
    {
        $field = "nachname";
        self::prepareForErrorParsing($field);
        $this->applicantInput->parse();
        $this->assertNotEmpty($this->applicantInput->showHasError($field));
    }

    public function testSuccessfulParsingOfFieldLastname()
    {
        $field = "nachname";
        self::prepareForSuccessfulParsing($field);
        $this->applicantInput->parse();
        $this->assertEquals('', $this->applicantInput->showHasError($field));
        $this->assertNotEmpty($this->applicantInput->showIsSuccess($field));
    }

    public function testErroneousParsingOfFieldStreet()
    {
        $field = "street";
        self::prepareForErrorParsing($field);
        $this->applicantInput->parse();
        $this->assertNotEmpty($this->applicantInput->showHasError($field));
    }

    public function testSuccessfulParsingOfFieldStreet()
    {
        $field = "street";
        self::prepareForSuccessfulParsing($field);
        $this->applicantInput->parse();
        $this->assertEquals('', $this->applicantInput->showHasError($field));
        $this->assertNotEmpty($this->applicantInput->showIsSuccess($field));
    }

    public function testErroneousParsingOfFieldHouseNumber()
    {
        $field = "houseno";
        self::prepareForErrorParsing($field);
        $this->applicantInput->parse();
        $this->assertNotEmpty($this->applicantInput->showHasError($field));
    }

    public function testSuccessfulParsingOfFieldHouseNumber()
    {
        $field = "houseno";
        self::prepareForSuccessfulParsing($field);
        $this->applicantInput->parse();
        $this->assertEquals('', $this->applicantInput->showHasError($field));
        $this->assertNotEmpty($this->applicantInput->showIsSuccess($field));
    }

    public function testErroneousParsingOfFieldZipCode()
    {
        $field = "plz";
        self::prepareForErrorParsing($field);
        $this->applicantInput->parse();
        $this->assertNotEmpty($this->applicantInput->showHasError($field));
    }

    public function testSuccessfulParsingOfFieldZipCode()
    {
        $field = "plz";
        self::prepareForSuccessfulParsing($field);
        $this->applicantInput->parse();
        $this->assertEquals('', $this->applicantInput->showHasError($field));
        $this->assertNotEmpty($this->applicantInput->showIsSuccess($field));
    }

    public function testErroneousParsingOfFieldCity()
    {
        $field = "city";
        self::prepareForErrorParsing($field);
        $this->applicantInput->parse();
        $this->assertNotEmpty($this->applicantInput->showHasError($field));
    }

    public function testSuccessfulParsingOfFieldCity()
    {
        $field = "city";
        self::prepareForSuccessfulParsing($field);
        $this->applicantInput->parse();
        $this->assertEquals('', $this->applicantInput->showHasError($field));
        $this->assertNotEmpty($this->applicantInput->showIsSuccess($field));
    }

    public function testErroneousParsingOfFieldCountry()
    {
        $field = "country";
        self::prepareForErrorParsing($field);
        $this->applicantInput->parse();
        $this->assertNotEmpty($this->applicantInput->showHasError($field));
    }

    public function testSuccessfulParsingOfFieldCountry()
    {
        $field = "country";
        self::prepareForSuccessfulParsing($field);
        $this->applicantInput->parse();
        $this->assertEquals('', $this->applicantInput->showHasError($field));
        $this->assertNotEmpty($this->applicantInput->showIsSuccess($field));
    }

    public function testErroneousParsingOfFieldDojo()
    {
        $field = "dojo";
        self::prepareForErrorParsing($field);
        $this->applicantInput->parse();
        $this->assertNotEmpty($this->applicantInput->showHasError($field));
    }

    public function testSuccessfulParsingOfFieldDojo()
    {
        $field = "dojo";
        self::prepareForSuccessfulParsing($field);
        $this->applicantInput->parse();
        $this->assertEquals('', $this->applicantInput->showHasError($field));
        $this->assertNotEmpty($this->applicantInput->showIsSuccess($field));
    }

    public function testErroneousParsingOfFieldTwaNumber()
    {
        $field = "twano";
        self::prepareForErrorParsing($field);
        $this->applicantInput->parse();
        $this->assertNotEmpty($this->applicantInput->showHasError($field));
    }

    public function testSuccessfulParsingOfFieldTwaNumber()
    {
        $field = "twano";
        self::prepareForSuccessfulParsing($field);
        $this->applicantInput->parse();
        $this->assertEquals('', $this->applicantInput->showHasError($field));
        $this->assertNotEmpty($this->applicantInput->showIsSuccess($field));
    }

    public function testErroneousParsingOfFieldGrading()
    {
        $field = "grad";
        self::prepareForErrorParsing($field);
        $this->applicantInput->parse();
        $this->assertNotEmpty($this->applicantInput->showHasError($field));
    }

    public function testSuccessfulParsingOfFieldGrading()
    {
        $field = "grad";
        self::prepareForSuccessfulParsing($field);
        $this->applicantInput->parse();
        $this->assertEquals('', $this->applicantInput->showHasError($field));
        $this->assertNotEmpty($this->applicantInput->showIsSuccess($field));
    }

    public function testErroneousParsingOfFieldGradingSince()
    {
        $field = "gsince";
        self::prepareForErrorParsing($field);
        $this->applicantInput->parse();
        $this->assertNotEmpty($this->applicantInput->showHasError($field));
    }

    public function testSuccessfulParsingOfFieldGradingSince()
    {
        $field = "gsince";
        self::prepareForSuccessfulParsing($field);
        $this->applicantInput->parse();
        $this->assertEquals('', $this->applicantInput->showHasError($field));
        $this->assertNotEmpty($this->applicantInput->showIsSuccess($field));
    }

    public function testErroneousParsingOfFieldRoom()
    {
        $field = "room";
        self::prepareForErrorParsing($field);
        $this->applicantInput->parse();
        $this->assertNotEmpty($this->applicantInput->showHasError($field));
    }

    public function testSuccessfulParsingOfFieldRoom()
    {
        $field = "room";
        self::prepareForSuccessfulParsing($field);
        $this->applicantInput->parse();
        $this->assertEquals('', $this->applicantInput->showHasError($field));
        $this->assertNotEmpty($this->applicantInput->showIsSuccess($field));
    }

}