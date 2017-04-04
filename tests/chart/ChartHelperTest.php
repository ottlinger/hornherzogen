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

    public function testGetDataRetrievalByCountry()
    {
        $week = "My Week";
        $json = $this->chartHelper->getByCountry($week);
        $this->assertNotNull($json);
        $this->assertContains($week, $json);
    }

    public function testGetDataRetrievalByGender()
    {
        $week = "My Week";
        $json = $this->chartHelper->getByGender($week);
        $this->assertNotNull($json);
        $this->assertContains($week, $json);
    }

}
