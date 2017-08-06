<?php

use hornherzogen\ui\ForkMe;
use PHPUnit\Framework\TestCase;

class ForkMeTest extends TestCase
{
    private $forkMe = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->forkMe = new ForkMe();
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        $this->forkMe = null;
    }

    /**
     * Test instance of.
     *
     * @test
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf('hornherzogen\ui\ForkMe', $this->forkMe);
    }

    public function testToStringContainsForkMeAndGitHubAddress()
    {
        $this->assertNotNull($this->forkMe);
        $toString = (string) $this->forkMe;
        $this->assertContains('hornherzogen', $toString);
        $this->assertContains('github', $toString);
        $this->assertContains('forkme_right_green_007200', $toString);
    }
}
