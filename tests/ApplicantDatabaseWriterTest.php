<?php
use hornherzogen\db\ApplicantDatabaseWriter;

// https://github.com/sebastianbergmann/dbunit/blob/2.0/samples/BankAccountDB/BankAccountDBTest.php

class ApplicantDatabaseWriterTest extends PHPUnit_Extensions_Database_TestCase
{
    private $writer = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->writer = new ApplicantDatabaseWriter();
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

    /**
     * Returns the test database connection.
     *
     * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    protected function getConnection()
    {
        // TODO: Implement getConnection() method.
    }

    /**
     * Returns the test dataset.
     *
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    protected function getDataSet()
    {
        // TODO: Implement getDataSet() method.
    }
}