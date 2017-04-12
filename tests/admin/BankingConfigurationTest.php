<?php
declare(strict_types=1);
use hornherzogen\admin\BankingConfiguration;
use PHPUnit\Framework\TestCase;

class BankingConfigurationTest extends TestCase
{
    private $bankConfiguration = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $GLOBALS['horncfg'] = NULL;
        $this->bankConfiguration = new BankingConfiguration();
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        $this->bankConfiguration = null;
    }

    /**
     * Test instance of.
     *
     * @test
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf('hornherzogen\admin\BankingConfiguration', $this->bankConfiguration);
    }

    public function testDefaultValuesAreAllNull()
    {
        $this->assertNull($this->bankConfiguration->getIban());
        $this->assertNull($this->bankConfiguration->getAccountHolder());
        $this->assertNull($this->bankConfiguration->getBic());
        $this->assertNull($this->bankConfiguration->getReasonForPayment());
    }

    public function testBankingConfigurationValuesAreProperlyExtractedFromTheConfiguration()
    {
        $iban = "DEWOOO";
        $accountholder = "C`mor Butts";
        $bic = "BICTOR";
        $reason = "There always is a reason, isn't there?";

        $GLOBALS['horncfg']['iban'] = $iban;
        $GLOBALS['horncfg']['bic'] = $bic;
        $GLOBALS['horncfg']['accountholder'] = $accountholder;
        $GLOBALS['horncfg']['reasonforpayment'] = $reason;

        $generator = new BankingConfiguration();

        $this->assertEquals($iban, $generator->getIban());
        $this->assertEquals($bic, $generator->getBic());
        $this->assertEquals($accountholder, $generator->getAccountHolder());
        $this->assertEquals($reason, $generator->getReasonForPayment());
    }
}
