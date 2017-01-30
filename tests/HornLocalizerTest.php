<?php
use PHPUnit\Framework\TestCase;

class HornLocalizerTest extends TestCase
{
    private $language = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->language = new hornherzogen\HornLocalizer();
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        $this->language = null;
    }

    /**
     * Test instance of.
     *
     * @test
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf('hornherzogen\HornLocalizer', $this->language);
    }

}