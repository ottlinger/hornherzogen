<?php
use hornherzogen\GitRevision;
use PHPUnit\Framework\TestCase;

class GitRevisionTest extends TestCase
{
    private $revision = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->revision = new GitRevision();
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        $this->revision = null;
    }

    /**
     * Test instance of.
     *
     * @test
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf('hornherzogen\GitRevision', $this->revision);
    }


    /**
     * Return gitrevision is not empty, either due to correct revision or dummy string.
     *
     * @test
     */
    public function testGitRevisionIsNeverEmpty()
    {
        $this->assertFalse(empty($this->revision->gitrevision()));
    }

}