<?php
use hornherzogen\admin\BankingConfiguration;
use PHPUnit\Framework\TestCase;

use hornherzogen\Applicant;

class BankingConfigurationTest extends TestCase
{
    private $generator = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->generator = new BankingConfiguration();
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        $this->generator = null;
    }

    /**
     * Test instance of.
     *
     * @test
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf('hornherzogen\admin\BankingConfiguration', $this->generator);
    }

    public function testSubjectRetrieval()
    {
        $this->assertNotNull($this->generator);
    }
}
