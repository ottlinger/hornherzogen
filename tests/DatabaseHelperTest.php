<?php
use hornherzogen\db\DatabaseHelper;
use PHPUnit\Framework\TestCase;

class DatabaseHelperTest extends TestCase
{
    private $helper = null;
    private $pdo = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->helper = new DatabaseHelper();
        $this->pdo = new PDO('sqlite::memory:');
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        $this->helper = null;
        $this->pdo = null;
    }

    /**
     * Test instance of.
     *
     * @test
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf('hornherzogen\db\DatabaseHelper', $this->helper);
    }

    public function testEmptyToNullWithNullArgument()
    {
        $this->assertNull($this->helper->emptyToNull(NULL));
    }

    public function testEmptyToNullWithNonNullArgument()
    {
        $this->assertEquals("asd dsa", $this->helper->emptyToNull("  asd dsa "));
    }

    public function testTrimAndMaskNull()
    {
        $this->assertNull($this->helper->trimAndMask(NULL));
    }

    public function testPreventSQLInjectionWithParameterNull()
    {
        $this->assertNull($this->helper->makeSQLCapable(NULL, NULL));
    }

    public function testPreventSQLInjectionWithParameterGiven()
    {
        $this->assertEquals("'no change needed'", $this->helper->makeSQLCapable("no change needed", $this->pdo));
    }

    public function testPreventSQLInjectionWithSqlInParameterGiven()
    {
        $this->assertEquals("' \%sdasd \_ff\_'", $this->helper->makeSQLCapable(" %sdasd _ff_", $this->pdo));
    }

    public function testPreventSQLInjectionWithParameterGivenWithoutDatabaseConnection()
    {
        $this->assertEquals("'no change needed'", $this->helper->makeSQLCapable("no change needed", NULL));
    }

    public function testPreventSQLInjectionWithSqlInParameterGivenWithoutDatabaseConnection()
    {
        $this->assertEquals("' \%sdasd \_ff\_'", $this->helper->makeSQLCapable(" %sdasd _ff_", NULL));
    }

    public function testMappingFromDatabaseToBeanIsComplete()
    {
        $row = array(
            'id' => '4711',
            'week' => 'week1',
            'gender' => 'male',
            'email' => 'foo@bar.de',
            'city' => 'Beijing',
            'country' => 'Doitsu',
            'vorname' => 'Hugo',
            'nachname' => 'Balder',
            'combinedName' => 'Hugo Balder',
            'street' => 'Up de straat',
            'houseno' => '3',
            'plz' => '12345',
            'dojo' => 'KaiShinKan',
            'grad' => '6.Kyu',
            'gradsince' => '1970-01-01',
            'twano' => 'UX-1',
            'room' => '1a',
            'together1' => 'p1',
            'together2' => 'p2',
            'essen' => 'veg',
            'flexible' => 'no',
            'additionals' => 'This is possible',
            'created' => '1970-02-01',
            'mailed' => '1970-02-02',
            'verified' => '1970-02-03',
            'paymentmailed' => '1970-02-04',
            'paymentreceived' => '1970-02-05',
            'booked' => '1970-02-06',
            'cancelled' => '1970-02-07',
            'statusId' => '47110815',
            'language' => 'co.jp',
        );
        $applicant = $this->helper->fromDatabaseToObject($row);
        $this->assertNotNull($applicant);

        $this->assertEquals('4711', $applicant->getPersistenceId());
        $this->assertEquals('1', $applicant->getWeek());
        $this->assertEquals('male', $applicant->getGender());
        $this->assertEquals('foo@bar.de', $applicant->getEmail());
        $this->assertEquals('Beijing', $applicant->getCity());
        $this->assertEquals('Doitsu', $applicant->getCountry());
        $this->assertEquals('Hugo', $applicant->getFirstname());
        $this->assertEquals('Balder', $applicant->getLastname());
        $this->assertEquals('Up de straat', $applicant->getStreet());
        $this->assertEquals('3', $applicant->getHousenumber());
        $this->assertEquals('12345', $applicant->getZipcode());
        $this->assertEquals('KaiShinKan', $applicant->getDojo());
        $this->assertEquals('6.Kyu', $applicant->getGrading());
        $this->assertEquals('1970-01-01', $applicant->getDateOfLastGrading());
        $this->assertEquals('1a', $applicant->getRoom());
        $this->assertEquals('p1', $applicant->getPartnerOne());
        $this->assertEquals('p2', $applicant->getPartnerTwo());
        $this->assertEquals('veg', $applicant->getFoodCategory());
        $this->assertFalse($applicant->getFlexible());
        $this->assertEquals('This is possible', $applicant->getRemarks());
        $this->assertEquals('1970-02-01', $applicant->getCreatedAt());
        $this->assertEquals('1970-02-02', $applicant->getMailedAt());
        $this->assertEquals('1970-02-03', $applicant->getConfirmedAt());
        $this->assertEquals('1970-02-04', $applicant->getPaymentRequestedAt());
        $this->assertEquals('1970-02-05', $applicant->getPaymentReceivedAt());
        $this->assertEquals('1970-02-06', $applicant->getBookedAt());
        $this->assertEquals('1970-02-07', $applicant->getCancelledAt());
        $this->assertEquals('47110815', $applicant->getCurrentStatus());
        $this->assertEquals('co.jp', $applicant->getLanguage());
    }

    public function testMappingEmptyRowFromDatabaseToPojo()
    {
        $applicant = $this->helper->fromDatabaseToObject(NULL);
        $this->assertNotNull($applicant);

        $applicant = $this->helper->fromDatabaseToObject(array());
        $this->assertNotNull($applicant);
    }

    public function testDatabaseLogErrorWithInvalidParameters() {
        // to avoid a warning that no assertions are in the test
        $this->assertNotNull($this->helper);

        $this->helper->logDatabaseErrors(NULL, NULL);
        $this->helper->logDatabaseErrors(FALSE, NULL);
        $this->helper->logDatabaseErrors(TRUE, NULL);
    }

    public function testDatabaseLogErrorWithValidParameters() {
        // to avoid a warning that no assertions are in the test
        $this->assertNotNull($this->helper);

        $this->helper->logDatabaseErrors(TRUE, $this->pdo);
        $this->helper->logDatabaseErrors(FALSE, $this->pdo);
    }

}