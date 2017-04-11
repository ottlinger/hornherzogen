<?php
declare(strict_types=1);
use hornherzogen\admin\BankingConfiguration;
use PHPUnit\Framework\TestCase;

class BankingConfigurationTest extends TestCase
{
    private $generator = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $GLOBALS['hornconfiguration'] = NULL;
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

    public function testDefaultValuesAreAllNull()
    {
        $this->assertNull($this->generator->getIban());
        $this->assertNull($this->generator->getAccountHolder());
        $this->assertNull($this->generator->getBic());
        $this->assertNull($this->generator->getReasonForPayment());
    }

    // TODO add positive test
/*
    public function testBankingConfigurationValuesAreProperlyExtractedFromTheConfiguration()
    {
    }
*/
}
