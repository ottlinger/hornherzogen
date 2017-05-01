<?php
use hornherzogen\ApplicantInput;
use hornherzogen\db\ApplicantDatabaseParser;
use PHPUnit\Framework\TestCase;

class ApplicantDatabaseParserTest extends TestCase
{
    private $writer = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $applicantInput = new ApplicantInput();
        $applicantInput->setWeek("1");
        $applicantInput->setCountry("DE");
        $applicantInput->setCity("Berlin");
        $applicantInput->setFirstname("Egon");
        $applicantInput->setLastname("Balder");
        $applicantInput->setCurrentStatus(1); // APPLIED
        $applicantInput->setLanguage("ru");
        $applicantInput->setGender("unknown");
        $applicantInput->setEmail("foo@bar.com");
        $applicantInput->setStreet("UpDeStraat");
        $applicantInput->setDojo("KaiShinKan");
        $applicantInput->setHouseNumber("1");
        $applicantInput->setZipCode("2");
        $applicantInput->setGrading("shodan");
        $applicantInput->setDateOfLastGrading("2017-01-01");
        $applicantInput->setTwaNumber("DE1234");
        $applicantInput->setRoom("1a");
        $applicantInput->setPartnerOne("P1");
        $applicantInput->setPartnerTwo("P2");
        $applicantInput->setFoodCategory("hungry");
        $applicantInput->setRemarks("Just a test");
        $applicantInput->setFlexible("yes");
        $this->writer = new ApplicantDatabaseParser($applicantInput);
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        $this->writer = null;
    }

    /**
     * Test instance of.
     *
     * @test
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf('hornherzogen\db\ApplicantDatabaseParser', $this->writer);
    }

    public function testParsingIntoSqlString()
    {
        $expected = "INSERT INTO applicants (language,week,gender,email,city,country,vorname,nachname,combinedName,street,houseno,plz,grad,gradsince,twano,room,together1,together2,essen,flexible,additionals,statusId,dojo) VALUES ('ru','1','unknown','foo@bar.com','Berlin','DE','Egon','Balder','Egon Balder','UpDeStraat','1','2','shodan','2017-01-01','DE1234','1a','P1','P2','hungry','1','Just a test',1,'KaiShinKan')";
        $this->assertStringStartsWith("INSERT INTO applicants (", $this->writer->getInsertIntoSql());
        $this->assertEquals($expected, $this->writer->getInsertIntoSql());
        $this->assertEquals(22, sizeof($this->writer->getInsertIntoValues()));
    }

    public function testParsingOfPotentiallyNullableStrings()
    {
        $this->assertFalse(boolval(NULL));
    }

}
