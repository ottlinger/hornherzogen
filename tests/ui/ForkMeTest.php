<?php
use hornherzogen\ui\ForkMe;
use PHPUnit\Framework\TestCase;

class ForkMeTest extends TestCase
{
    private $chartHelper = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->chartHelper = new ForkMe();
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
        $this->assertInstanceOf('hornherzogen\ui\ForkMe', $this->chartHelper);
    }

    public function testToStringContainsForkMeAndGitHubAddress()
    {
        $this->assertNotNull($this->chartHelper);
        $toString = (string)$this->chartHelper;
        $this->assertContains("hornherzogen", $toString);
        $this->assertContains("github", $toString);
        $this->assertContains("forkme_right_green_007200", $toString);
    }

}
