<?php
use hornherzogen\db\ApplicantDatabaseWriter;
use hornherzogen\ApplicantInput;
use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;
// https://github.com/sebastianbergmann/dbunit/blob/2.0/samples/BankAccountDB/BankAccountDBTest.php

class ApplicantDatabaseWriterTest extends TestCase
{
    use TestCaseTrait;
    
    private $writer = null;
    private $pdo = null;


    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->pdo = new PDO('sqlite::memory:');
//        $this->writer = new ApplicantDatabaseWriter($this->pdo);
        $this->writer = new ApplicantDatabaseWriter();
        self::createTable($this->pdo);
    }

    // without foreign key constraints to ease testing
    static public function createTable(PDO $pdo)
    {
        $query = "
        CREATE TABLE IF NOT EXISTS `applicants` (
          `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
          `week` varchar(50) COLLATE utf8_bin DEFAULT NULL,
          `gender` varchar(10) COLLATE utf8_bin DEFAULT NULL,
          `vorname` varchar(200) COLLATE utf8_bin DEFAULT NULL,
          `nachname` varchar(200) COLLATE utf8_bin DEFAULT NULL,
          `combinedName` varchar(400) COLLATE utf8_bin DEFAULT NULL,
          `street` varchar(250) COLLATE utf8_bin DEFAULT NULL,
          `houseno` varchar(20) COLLATE utf8_bin DEFAULT NULL,
          `plz` varchar(20) COLLATE utf8_bin DEFAULT NULL,
          `city` varchar(250) COLLATE utf8_bin DEFAULT NULL,
          `country` varchar(250) COLLATE utf8_bin DEFAULT NULL,
          `email` varchar(250) COLLATE utf8_bin DEFAULT NULL,
          `dojo` varchar(256) COLLATE utf8_bin DEFAULT NULL,
          `grad` varchar(20) COLLATE utf8_bin DEFAULT NULL,
          `gradsince` date DEFAULT NULL,
          `twano` varchar(20) COLLATE utf8_bin DEFAULT NULL,
          `room` varchar(20) COLLATE utf8_bin DEFAULT NULL,
          `together1` varchar(100) COLLATE utf8_bin DEFAULT NULL,
          `together2` varchar(100) COLLATE utf8_bin DEFAULT NULL,
          `essen` varchar(20) COLLATE utf8_bin DEFAULT NULL,
          `flexible` tinyint(1) DEFAULT NULL,
          `additionals` varchar(1024) COLLATE utf8_bin DEFAULT NULL,
          `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
          `mailed` timestamp NULL,
          `verified` timestamp NULL,
          `paymentmailed` timestamp NULL,
          `paymentreceived` timestamp NULL,
          `booked` timestamp NULL,
          `cancelled` timestamp NULL,
          `statusId` int(10) unsigned NOT NULL,
          PRIMARY KEY (`id`),
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
        ";
        $pdo->query($query);
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
        $this->assertInstanceOf('hornherzogen\db\ApplicantDatabaseWriter', $this->writer);
    }

    public function testWithoutConfigEmptyListIsRetrievedWithoutWeekParameter()
    {
        // may return results locally :-D
        $this->assertEquals(0, sizeof($this->writer->getAllByWeek()));
    }

    public function testWithoutConfigEmptyListIsRetrievedWithWeekParameter()
    {
        $this->assertEquals(0, sizeof($this->writer->getAllByWeek("week1")));
    }

    public function testRetrieveNameMailCombination() {
        $result = $this->writer->getByNameAndMailadress('Hugo','Hirsch','foo@bar.de');
        $this->assertNull($result);
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
        $applicant = $this->writer->fromDatabaseToObject($row);
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
        $applicant = $this->writer->fromDatabaseToObject(NULL);
        $this->assertNotNull($applicant);

        $applicant = $this->writer->fromDatabaseToObject(array());
        $this->assertNotNull($applicant);
    }

    public function testRemoveByIdWithoutDatabase() {
        $this->assertEquals(0, $this->writer->removeById("wwewewe"));
    }

    public function testPersistWithoutDatabaseYieldsDummyValue() {
        $applicant = new ApplicantInput();
        $this->assertEquals(4711, $this->writer->persist($applicant));
    }

    /**
     * Returns the test database connection.
     *
     * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    protected function getConnection()
    {
        return $this->pdo;
    }

    /**
     * Returns the test dataset.
     *
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    protected function getDataSet()
    {
        return $this->createFlatXMLDataSet(dirname(__FILE__) . '/fixtures/applicants.xml');
    }

}