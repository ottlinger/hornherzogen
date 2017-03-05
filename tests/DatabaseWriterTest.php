<?php
use PHPUnit\Framework\TestCase;
use hornherzogen\DatabaseWriter;

class DatabaseWriterTest extends TestCase
{
    private $writer = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->writer = new DatabaseWriter();
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
        $this->assertInstanceOf('hornherzogen\DatabaseWriter', $this->writer);
    }

}
