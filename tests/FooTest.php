<?php
use PHPUnit\Framework\TestCase;
use hornherzogen\Foo;

class FooTest extends TestCase
{
    private $calc = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->calc = new Foo;
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        $this->calc = null;
    }

    /**
     * Test instance of $this->calc
     *
     * @test
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf('hornherzogen\Foo', $this->calc);
    }

    /**
     * Test calculation return a true sum.
     *
     * @test
     */
    public function testValidResult()
    {
        $this->assertEquals(4711, $this->calc->add());
    }
}
