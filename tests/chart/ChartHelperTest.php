<?php
use hornherzogen\chart\ChartHelper;
use PHPUnit\Framework\TestCase;

class ChartHelperTest extends TestCase
{
    private $reader = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->reader = new ChartHelper();
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
        $this->assertInstanceOf('hornherzogen\chart\ChartHelper', $this->reader);
    }

    public function testGetDataRetrievalByGender()
    {
        $this->assertNotNull($this->reader->getByGender());
    }

    public function testGetDataRetrievalByWeek()
    {
        $week = "My Week";
        $json = $this->reader->getByWeek($week);
        $this->assertNotNull($this->reader->getByWeek($week));
        $this->assertContains($week, $json);
    }

}
