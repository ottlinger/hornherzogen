<?php

use hornherzogen\ApplicantInput;
use PHPUnit\Framework\TestCase;

class ApplicantInputTest extends TestCase
{
    private $applicantInput = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->applicantInput = new ApplicantInput();
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

    public function testGetAndSetFieldEmailcheck()
    {
        $value = 'emailCheck@example.com';
        $this->applicantInput->setEmailcheck($value);
        $this->assertEquals($value, $this->applicantInput->getEmailcheck());
    }

    public function testIsAlwaysErrorSinceNoSuccess()
    {
        $this->applicantInput->parse();
        $this->assertTrue($this->applicantInput->hasParseErrors());
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
        $_POST['vorname'] = '  <b>My firstname</b> ';
        $this->applicantInput->parse();
        $this->assertEquals('&lt;b&gt;My firstname&lt;/b&gt;', $this->applicantInput->getFirstName());
    }

    public function testNumberOfFieldsRequiredInWebFormDidChange()
    {
        $this->assertEquals(16, count(ApplicantInput::getRequiredFields()));
    }

    public function testNoMailaddressesGivenResultsInError()
    {
        $_POST = [];
        $this->assertFalse($this->applicantInput->areEmailAddressesValid());
    }

    public function testNoMatchingMailaddressesGivenResultsInError()
    {
        $_POST = [];
        $_POST['email'] = 'justATypoe@example.com';
        $_POST['emailcheck'] = 'justATypo@example.com';
        $this->assertFalse($this->applicantInput->areEmailAddressesValid());
    }

    public function testMatchingStringsButNoMailaddressesGivenResultsInError()
    {
        $_POST = [];
        $_POST['email'] = 'example.com';
        $_POST['emailcheck'] = 'example.com';
        $this->assertFalse($this->applicantInput->areEmailAddressesValid());
    }

    public function testMatchingStringsAndValidMailaddressesGivenWorks()
    {
        $_POST = [];
        $_POST['email'] = 'foo@example.com';
        $_POST['emailcheck'] = 'foo@example.com';
        $this->assertTrue($this->applicantInput->areEmailAddressesValid());
    }

    public function testToStringAfterInit()
    {
        $_POST = [];
        $this->applicantInput->parse();
        $toString = $this->applicantInput->__toString();

        $this->assertContains('ERROR', $toString);
        $this->assertContains('SUCCESS', $toString);
        $this->assertContains('hasParseErrors? 1', $toString);
    }

    public function testErroneousParsingOfFieldWeek()
    {
        $field = 'week';
        self::prepareForErrorParsing($field);
        $this->applicantInput->parse();
        $this->assertNotEmpty($this->applicantInput->showHasError($field));
    }

    private static function prepareForErrorParsing($field)
    {
        $_POST = [];
        $_POST[$field] = [];
    }

    public function testSuccessfulParsingOfFieldWeek()
    {
        $field = 'week';
        self::prepareForSuccessfulParsing($field);
        $this->applicantInput->parse();
        $this->assertEquals('', $this->applicantInput->showHasError($field));
        $this->assertNotEmpty($this->applicantInput->showIsSuccess($field));
    }

    private static function prepareForSuccessfulParsing($field)
    {
        $_POST = [];
        $_POST[$field] = $field;
    }

    public function testErroneousParsingOfFieldFlexible()
    {
        $field = 'flexible';
        self::prepareForErrorParsing($field);
        $this->applicantInput->parse();
        $this->assertNotEmpty($this->applicantInput->showHasError($field));
    }

    public function testSuccessfulParsingOfFieldFlexible()
    {
        $field = 'flexible';
        self::prepareForSuccessfulParsing($field);
        $this->applicantInput->parse();
        $this->assertEquals('', $this->applicantInput->showHasError($field));
        $this->assertNotEmpty($this->applicantInput->showIsSuccess($field));
    }

    public function testErroneousParsingOfFieldGender()
    {
        $field = 'gender';
        self::prepareForErrorParsing($field);
        $this->applicantInput->parse();
        $this->assertNotEmpty($this->applicantInput->showHasError($field));
    }

    public function testSuccessfulParsingOfFieldGender()
    {
        $field = 'gender';
        self::prepareForSuccessfulParsing($field);
        $this->applicantInput->parse();
        $this->assertEquals('', $this->applicantInput->showHasError($field));
        $this->assertNotEmpty($this->applicantInput->showIsSuccess($field));
    }

    public function testErroneousParsingOfFieldLastname()
    {
        $field = 'nachname';
        self::prepareForErrorParsing($field);
        $this->applicantInput->parse();
        $this->assertNotEmpty($this->applicantInput->showHasError($field));
    }

    public function testSuccessfulParsingOfFieldLastname()
    {
        $field = 'nachname';
        self::prepareForSuccessfulParsing($field);
        $this->applicantInput->parse();
        $this->assertEquals('', $this->applicantInput->showHasError($field));
        $this->assertNotEmpty($this->applicantInput->showIsSuccess($field));
    }

    public function testErroneousParsingOfFieldStreet()
    {
        $field = 'street';
        self::prepareForErrorParsing($field);
        $this->applicantInput->parse();
        $this->assertNotEmpty($this->applicantInput->showHasError($field));
    }

    public function testSuccessfulParsingOfFieldStreet()
    {
        $field = 'street';
        self::prepareForSuccessfulParsing($field);
        $this->applicantInput->parse();
        $this->assertEquals('', $this->applicantInput->showHasError($field));
        $this->assertNotEmpty($this->applicantInput->showIsSuccess($field));
    }

    public function testErroneousParsingOfFieldHouseNumber()
    {
        $field = 'houseno';
        self::prepareForErrorParsing($field);
        $this->applicantInput->parse();
        $this->assertNotEmpty($this->applicantInput->showHasError($field));
    }

    public function testSuccessfulParsingOfFieldHouseNumber()
    {
        $field = 'houseno';
        self::prepareForSuccessfulParsing($field);
        $this->applicantInput->parse();
        $this->assertEquals('', $this->applicantInput->showHasError($field));
        $this->assertNotEmpty($this->applicantInput->showIsSuccess($field));
    }

    public function testErroneousParsingOfFieldZipCode()
    {
        $field = 'plz';
        self::prepareForErrorParsing($field);
        $this->applicantInput->parse();
        $this->assertNotEmpty($this->applicantInput->showHasError($field));
    }

    public function testSuccessfulParsingOfFieldZipCode()
    {
        $field = 'plz';
        self::prepareForSuccessfulParsing($field);
        $this->applicantInput->parse();
        $this->assertEquals('', $this->applicantInput->showHasError($field));
        $this->assertNotEmpty($this->applicantInput->showIsSuccess($field));
    }

    public function testErroneousParsingOfFieldCity()
    {
        $field = 'city';
        self::prepareForErrorParsing($field);
        $this->applicantInput->parse();
        $this->assertNotEmpty($this->applicantInput->showHasError($field));
    }

    public function testSuccessfulParsingOfFieldCity()
    {
        $field = 'city';
        self::prepareForSuccessfulParsing($field);
        $this->applicantInput->parse();
        $this->assertEquals('', $this->applicantInput->showHasError($field));
        $this->assertNotEmpty($this->applicantInput->showIsSuccess($field));
    }

    public function testErroneousParsingOfFieldCountry()
    {
        $field = 'country';
        self::prepareForErrorParsing($field);
        $this->applicantInput->parse();
        $this->assertNotEmpty($this->applicantInput->showHasError($field));
    }

    public function testSuccessfulParsingOfFieldCountry()
    {
        $field = 'country';
        self::prepareForSuccessfulParsing($field);
        $this->applicantInput->parse();
        $this->assertEquals('', $this->applicantInput->showHasError($field));
        $this->assertNotEmpty($this->applicantInput->showIsSuccess($field));
    }

    public function testErroneousParsingOfFieldDojo()
    {
        $field = 'dojo';
        self::prepareForErrorParsing($field);
        $this->applicantInput->parse();
        $this->assertNotEmpty($this->applicantInput->showHasError($field));
    }

    public function testSuccessfulParsingOfFieldDojo()
    {
        $field = 'dojo';
        self::prepareForSuccessfulParsing($field);
        $this->applicantInput->parse();
        $this->assertEquals('', $this->applicantInput->showHasError($field));
        $this->assertNotEmpty($this->applicantInput->showIsSuccess($field));
    }

    public function testSuccessfulParsingOfFieldTwaNumberNonMandatory()
    {
        $field = 'twano';
        self::prepareForErrorParsing($field);
        $this->applicantInput->parse();
        $this->assertEmpty($this->applicantInput->showHasError($field));

        self::prepareForSuccessfulParsing($field);
        $this->applicantInput->parse();
        $this->assertEquals('', $this->applicantInput->showHasError($field));
        $this->assertNotEmpty($this->applicantInput->showIsSuccess($field));
    }

    public function testErroneousParsingOfFieldGrading()
    {
        $field = 'grad';
        self::prepareForErrorParsing($field);
        $this->applicantInput->parse();
        $this->assertNotEmpty($this->applicantInput->showHasError($field));
    }

    public function testSuccessfulParsingOfFieldGrading()
    {
        $field = 'grad';
        self::prepareForSuccessfulParsing($field);
        $this->applicantInput->parse();
        $this->assertEquals('', $this->applicantInput->showHasError($field));
        $this->assertNotEmpty($this->applicantInput->showIsSuccess($field));
    }

    public function testErroneousParsingOfFieldGradingSince()
    {
        $field = 'gsince';
        self::prepareForErrorParsing($field);
        $this->applicantInput->parse();
        $this->assertNotEmpty($this->applicantInput->showHasError($field));
    }

    public function testSuccessfulParsingOfFieldGradingSince()
    {
        $field = 'gsince';
        self::prepareForSuccessfulParsing($field);
        $this->applicantInput->parse();
        $this->assertEquals('', $this->applicantInput->showHasError($field));
        $this->assertNotEmpty($this->applicantInput->showIsSuccess($field));
    }

    public function testErroneousParsingOfFieldRoom()
    {
        $field = 'room';
        self::prepareForErrorParsing($field);
        $this->applicantInput->parse();
        $this->assertNotEmpty($this->applicantInput->showHasError($field));
    }

    public function testSuccessfulParsingOfFieldRoom()
    {
        $field = 'room';
        self::prepareForSuccessfulParsing($field);
        $this->applicantInput->parse();
        $this->assertEquals('', $this->applicantInput->showHasError($field));
        $this->assertNotEmpty($this->applicantInput->showIsSuccess($field));
    }

    public function testErroneousAndSuccessfulParsingOfFieldRoomOneNotMandatory()
    {
        $field = 'together1';
        self::prepareForErrorParsing($field);
        $this->applicantInput->parse();
        $this->assertEmpty($this->applicantInput->showHasError($field));

        self::prepareForSuccessfulParsing($field);
        $this->applicantInput->parse();
        $this->assertEquals('', $this->applicantInput->showHasError($field));
        $this->assertNotEmpty($this->applicantInput->showIsSuccess($field));
    }

    public function testErroneousAndSuccessfulParsingOfFieldRoomTwoNotMandatory()
    {
        $field = 'together2';
        self::prepareForErrorParsing($field);
        $this->applicantInput->parse();
        $this->assertEmpty($this->applicantInput->showHasError($field));

        self::prepareForSuccessfulParsing($field);
        $this->applicantInput->parse();
        $this->assertEquals('', $this->applicantInput->showHasError($field));
        $this->assertNotEmpty($this->applicantInput->showIsSuccess($field));
    }

    public function testErroneousParsingOfFoodCategory()
    {
        $field = 'essen';
        self::prepareForErrorParsing($field);
        $this->applicantInput->parse();
        $this->assertNotEmpty($this->applicantInput->showHasError($field));
        $this->assertTrue($this->applicantInput->hasParseErrors());
    }

    public function testSuccessfulParsingOfFieldFoodCategory()
    {
        $field = 'essen';
        self::prepareForSuccessfulParsing($field);
        $this->applicantInput->parse();
        $this->assertEquals('', $this->applicantInput->showHasError($field));
        $this->assertNotEmpty($this->applicantInput->showIsSuccess($field));
    }

    public function testSuccessfulParsingOfFieldAdditionalsNotMandatory()
    {
        $field = 'additionals';
        self::prepareForErrorParsing($field);
        $this->applicantInput->parse();
        $this->assertEmpty($this->applicantInput->showHasError($field));

        self::prepareForSuccessfulParsing($field);
        $this->applicantInput->parse();
        $this->assertEquals('', $this->applicantInput->showHasError($field));
        $this->assertNotEmpty($this->applicantInput->showIsSuccess($field));
    }

    public function testLanguageIsSetImplicitlyDuringParsingToDefaultLanguage()
    {
        $this->applicantInput->parse();

        $this->assertEquals('de', $this->applicantInput->getLanguage());
    }

    public function testIfAllMandatoryFieldsAreExistingWithoutParsingFromUserInputObjectHasNoErrors()
    {
        // MANDATORY: set all mandatory fields
        $this->applicantInput->setFlexible(null);
        $this->applicantInput->setGender('none');
        $this->applicantInput->setFirstName('First');
        $this->applicantInput->setLastName('Name');
        $this->applicantInput->setStreet('Up de Straat');
        $this->applicantInput->setHouseNumber('17');
        $this->applicantInput->setZipCode('04600');
        $this->applicantInput->setCity('Haarlem');
        $this->applicantInput->setCountry('Netherlands');
        $this->applicantInput->setEmail('abc@example.com');
        $this->applicantInput->setDojo('My little big dojo');
        $this->applicantInput->setGrading('ikkyu');
        $this->applicantInput->setDateOfLastGrading('2017-02-14');
        $this->applicantInput->setRoom('single');
        $this->applicantInput->setFoodCategory('none');
        $this->applicantInput->setWeek('week2');
        $this->applicantInput->setMailSent(true);
        // make sure this test fails if any configuration of required fields changes
        $this->assertEquals(16, count($this->applicantInput->getRequiredFields()));

        // since we did not extract the data from $_POST
        $this->assertFalse($this->applicantInput->hasParseErrors());
        $this->assertFalse($this->applicantInput->showFormButtons());

        $this->assertFalse($this->applicantInput->hasErrors());
        $this->assertEmpty($this->applicantInput->showHasError('week'));
    }

    public function testMandatoryFieldMissingButOptionalExistingResultsInErrors()
    {
        // OPTIONAL
        $this->applicantInput->setRemarks('This field is optional');
        $this->applicantInput->setTwaNumber('This field is optional');

        // MANDATORY: flexible is missing
        $this->applicantInput->setFirstName('First');
        $this->applicantInput->setLastName('Name');
        $this->applicantInput->setStreet('Up de Straat');
        $this->applicantInput->setHouseNumber('17');
        $this->applicantInput->setZipCode('04600');
        $this->applicantInput->setCity('Haarlem');
        $this->applicantInput->setCountry('Netherlands');
        $this->applicantInput->setEmail('abc@example.com');
        $this->applicantInput->setDojo('My little big dojo');
        $this->applicantInput->setGrading('ikkyu');
        $this->applicantInput->setGrading('week1');
        $this->applicantInput->setDateOfLastGrading('2017-02-14');
        $this->applicantInput->setRoom('single');
        $this->applicantInput->setFoodCategory('none');
        $this->applicantInput->setWeek('week2');

        // since we did not extract the data from $_POST
        $this->assertFalse($this->applicantInput->hasParseErrors());

        $this->assertEquals('', $this->applicantInput->showHasError('week'));
        $this->assertTrue($this->applicantInput->hasErrors());
        $this->assertTrue($this->applicantInput->showFormButtons());
    }

    public function testFlexibilityParsing()
    {
        $this->applicantInput->setFlexible('yes');
        $this->assertTrue($this->applicantInput->getFlexible());

        $this->applicantInput->setFlexible('1');
        $this->assertTrue($this->applicantInput->getFlexible());

        $this->applicantInput->setFlexible('no');
        $this->assertFalse($this->applicantInput->getFlexible());

        $this->applicantInput->setFlexible('anythingElseIsEMappedToNo');
        $this->assertFalse($this->applicantInput->getFlexible());
    }

    public function testFlexibilityParsingFromUserInputWithNotFlexible()
    {
        $_POST['flexible'] = 'no';
        $this->applicantInput->parse();

        $this->assertFalse($this->applicantInput->getFlexible());

        $_POST['flexible'] = null;
        $this->applicantInput->parse();

        $this->assertFalse($this->applicantInput->getFlexible());
    }

    public function testFlexibilityParsingFromUserInputWithBeingFlexible()
    {
        $_POST['flexible'] = 'yes';
        $this->applicantInput->parse();

        $this->assertTrue($this->applicantInput->getFlexible());
    }

    public function testFullnameGenerationWithSaltIsTrimmed()
    {
        $this->applicantInput->setFirstname(' first');
        $this->applicantInput->setLastname('last ');
        $this->applicantInput->setFullname('       ');
        $this->assertEquals('first last', $this->applicantInput->getFullName());

        $this->applicantInput->setFullName('name');
        $this->assertEquals('first last  name', $this->applicantInput->getFullName());
        $this->assertEquals('first last  name', $this->applicantInput->getFullName());
        $this->assertEquals('first last  name', $this->applicantInput->getFullName());
    }

    public function testUIResponseForUnknownField()
    {
        $this->assertEmpty($this->applicantInput->getUIResponse('unknown'));
    }

    public function testUIResponseForFieldInStateOkayAndIsIdempotent()
    {
        $this->applicantInput->addSuccess('unknown');
        $this->applicantInput->addSuccess('unknown');
        $this->applicantInput->addSuccess('unknown');
        $this->applicantInput->addSuccess('unknown');
        $this->applicantInput->addSuccess('unknown');
        $this->assertEquals(' has-success has-feedback', $this->applicantInput->getUIResponse('unknown'));
    }

    public function testUIResponseForFieldInStateErrorAndIsIdempotent()
    {
        $this->applicantInput->addError('unknown');
        $this->applicantInput->addError('unknown');
        $this->applicantInput->addError('unknown');
        $this->applicantInput->addError('unknown');
        $this->assertEquals(1, $this->applicantInput->getErrorCount());
        $this->assertEquals(' has-error has-feedback', $this->applicantInput->getUIResponse('unknown'));
    }

    public function testSymbolRetrievalInUIInputTextFieldForUnknownField()
    {
        $this->assertEmpty($this->applicantInput->showSymbolIfFeedback('unknown'));
    }

    public function testSymbolRetrievalInUIInputTextFieldForFieldInStateOk()
    {
        $this->applicantInput->addSuccess('unknown');
        $this->assertEquals('<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>', $this->applicantInput->showSymbolIfFeedback('unknown'));
    }

    public function testSymbolRetrievalInUIInputTextFieldForFieldInStateError()
    {
        $this->applicantInput->addError('unknown');
        $this->assertEquals('<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>', $this->applicantInput->showSymbolIfFeedback('unknown'));
    }

    public function testBooleanMarkerInCaseNoParsingTookPlace()
    {
        $this->assertFalse($this->applicantInput->hasParsingHappened());

        $this->applicantInput->parse();

        $this->assertTrue($this->applicantInput->hasParsingHappened());
    }
}
