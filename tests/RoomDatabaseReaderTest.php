<?php

use hornherzogen\db\ApplicantDatabaseReader;
use PHPUnit\Framework\TestCase;

class RoomDatabaseReaderTest extends TestCase
{
    private $reader = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->reader = new ApplicantDatabaseReader();
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
        $this->assertEquals(4, sizeof($this->reader->listByRoomCategoryPerWeek(NULL)));
    }

    public function testFoodReservations()
    {
        $this->assertEmpty($this->reader->listByFoodCategoryPerWeek(NULL));
    }


}
