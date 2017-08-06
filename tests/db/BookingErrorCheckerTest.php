<?php

use hornherzogen\db\BookingErrorChecker;
use PHPUnit\Framework\TestCase;

class BookingErrorCheckerTest extends TestCase
{
    private $errorChecker = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->errorChecker = new BookingErrorChecker();
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        $this->errorChecker = null;
    }

    /**
     * Test instance of.
     *
     * @test
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf('hornherzogen\db\BookingErrorChecker', $this->errorChecker);
    }

    public function testListRoomBookings()
    {
        $this->assertEmpty($this->errorChecker->listRoomBookings());
    }

    public function testRemoveByIdWithoutId()
    {
        $this->assertEmpty($this->errorChecker->removeById(null));
    }

    public function testRemoveByIdWithId()
    {
        $this->assertEmpty($this->errorChecker->removeById(4711));
    }

    public function testListDoubleBookings()
    {
        $this->assertEmpty($this->errorChecker->listDoubleBookings());
    }

    public function testListOverbookedBookings()
    {
        $this->assertEmpty($this->errorChecker->listOverbookedBookings());
    }

    public function testListPeopleWithBookingsThatDoNotTakePartInTheSeminar()
    {
        $this->assertEmpty($this->errorChecker->listPeopleWithBookingsThatDoNotTakePartInTheSeminar());
    }

    public function testListPeopleWithFinalStateButNoRooms()
    {
        $this->assertEmpty($this->errorChecker->listPeopleWithFinalStateButNoRooms());
    }
}
