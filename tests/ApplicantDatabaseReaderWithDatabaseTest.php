<?php

use hornherzogen\db\ApplicantDatabaseReader;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\Framework\TestCase;

class ApplicantDatabaseReaderWithDatabaseTest extends TestCase
{
    use TestCaseTrait;

    private $reader = null;
    private $pdo = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->pdo = new PDO('sqlite::memory:');
        $this->reader = new ApplicantDatabaseReader();
        self::createTable($this->pdo);
    }

    // without foreign key constraints to ease testing
    public static function createTable(PDO $pdo)
    {
        $query = '
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
        ';
        $pdo->query($query);
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        $this->reader = null;
    }

    /**
     * Test instance of.
     *
     * @test
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf('hornherzogen\db\ApplicantDatabaseReader', $this->reader);
    }

    public function testRoomWishesResultStructure()
    {
        $this->assertEquals(4, count($this->reader->listByRoomCategoryPerWeek(null)));
    }

    public function testFoodReservations()
    {
        $this->assertEmpty($this->reader->listByFoodCategoryPerWeek(null));
    }

    public function testGetById()
    {
        $this->assertEmpty($this->reader->getById(null));
        $this->assertEmpty($this->reader->getById(4711));
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
        return $this->createFlatXMLDataSet(dirname(__FILE__).'/fixtures/applicants.xml');
    }
}
