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

    public function testEmptyToNullWithNullArgument()
    {
        $this->assertNull($this->writer->emptyToNull(NULL));
    }

    public function testEmptyToNullWithNonNullArgument()
    {
        $this->assertEquals("asd dsa", $this->writer->emptyToNull("  asd dsa "));
    }

    public function testParsingIntoSqlString() {
        $this->assertStringStartsWith("INSERT INTO applicants (", $this->writer->getInsertIntoSql());
        $this->assertEquals("INSERT INTO applicants (language,week,city,country,vorname,nachname,combinedName,statusId) VALUES ('en','1','Berlin','DE','Egon','Balder','Egon Balder',1)", $this->writer->getInsertIntoSql());
        $this->assertEquals(8, sizeof($this->writer->getInsertIntoValues()));
    }

    public function testParsingOfPotentiallyNullableStrings() {
        $this->assertFalse(boolval(NULL));
    }

}
