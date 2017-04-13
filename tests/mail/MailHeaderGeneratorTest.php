<?php
use hornherzogen\mail\MailHeaderGenerator;
use PHPUnit\Framework\TestCase;

class MailHeaderGeneratorTest extends TestCase
{
    private $headerGenerator = null;

    /**
     * Setup the test environment and provide an ApplicantInput with all relevant fields set.
     */
    public function setUp()
    {
        $this->headerGenerator = new MailHeaderGenerator();
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        $this->headerGenerator = null;
    }

    /**
     * Test type of instance of $this->headerGenerator
     *
     * @test
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf('hornherzogen\mail\MailHeaderGenerator', $this->headerGenerator);
    }

}
