<?php
use hornherzogen\db\DatabaseHelper;
use PHPUnit\Framework\TestCase;

class DatabaseHelperTest extends TestCase
{
    private $helper = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->helper = new DatabaseHelper();
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        $this->helper = null;
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
        $this->assertNull($this->helper->trimAndmask(NULL));
    }
}