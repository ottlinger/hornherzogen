<?php
use hornherzogen\chart\ChartHelper;
use PHPUnit\Framework\TestCase;

class ChartHelperTest extends TestCase
{
    private $chartHelper = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->chartHelper = new ChartHelper();
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        $this->chartHelper = null;
    }

    /**
     * Test instance of.
     *
     * @test
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf('hornherzogen\chart\ChartHelper', $this->chartHelper);
    }

    public function testGetDataRetrievalByGender()
    {
        $this->assertNotNull($this->chartHelper->getByGender());
    }

    public function testGetDataRetrievalByWeek()
    {
        $week = "My Week";
        $json = $this->chartHelper->getByWeek($week);
        $this->assertNotNull($this->chartHelper->getByWeek($week));
        $this->assertContains($week, $json);
    }

}
