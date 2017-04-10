<?php
use hornherzogen\admin\FlexibilityMailGenerator;
use PHPUnit\Framework\TestCase;

use hornherzogen\Applicant;

class FlexibilityMailGeneratorTest extends TestCase
{
    private $generator = null;
    private $applicant = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->applicant = new Applicant();
        $this->generator = new FlexibilityMailGenerator($this->applicant);
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        $this->generator = null;
        $this->applicant = null;
    }

    /**
     * Test instance of.
     *
     * @test
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf('hornherzogen\admin\FlexibilityMailGenerator', $this->generator);
    }

    public function testSubjectRetrieval()
    {
        $this->assertNotNull($this->generator);
        $this->assertNotNull($this->generator->getSubject());
    }

    public function testBodyRetrieval()
    {
        $this->assertNotNull($this->generator);
        $this->assertNotNull($this->generator->getBody());
    }

}
