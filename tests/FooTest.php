<?php
class FooTest extends PHPUnit_Framework_TestCase {
	private $calc = null;

	/**
	 * Setup the test environment.
	 */
	public function setUp()
	{
		$this->calc = new JomWeb\Calculator;
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

}
