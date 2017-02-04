<?php
use PHPUnit\Framework\TestCase;

class ApplicantTest extends TestCase
{
    private $applicant = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->applicant = new hornherzogen\Applicant();
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        $this->applicant = null;
    }

    /**
     * Test instance of.
     *
     * @test
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf('hornherzogen\Applicant', $this->applicant);
    }
}
