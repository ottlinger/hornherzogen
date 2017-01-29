<?php
use PHPUnit\Framework\TestCase;

class ConfigurationWrapperTest extends TestCase
{
    private $configuration = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->configuration = new hornherzogen\ConfigurationWrapper;
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        $this->configuration = null;
    }

    /**
     * Test instance of.
     *
     * @test
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf('hornherzogen\ConfigurationWrapper', $this->configuration);
    }

    /**
     * Return trimmed mail value from config.
     *
     * @test
     */
    public function testMailIsReturnedTrimmedFromConfigFile()
    {
        $GLOBALS['horncfg']['mail'] = "mymail ";
        $this->assertEquals('mymail', $this->configuration->mail());
    }

    /**
     * Return empty mail value if configuration is not set
     *
     * @test
     */
    public function testMailIsEmptyIfNotConfigured()
    {
        $GLOBALS['horncfg'] = null;
        $this->assertEmpty($this->configuration->mail());

        $GLOBALS['horncfg'] = [];
        $this->assertEmpty($this->configuration->mail());
    }


    /**
     * Return pdf value from config.
     *
     * @test
     */
    public function testPdfIsReturnedFromConfigFile()
    {
        $GLOBALS['horncfg']['pdf'] = "my.pdf";
        $this->assertEquals('my.pdf', $this->configuration->pdf());
    }

    /**
     * Return empty pdf value if configuration is not set
     *
     * @test
     */
    public function testPdfIsEmptyIfNotConfigured()
    {
        $GLOBALS['horncfg'] = null;
        $this->assertEmpty($this->configuration->pdf());

        $GLOBALS['horncfg'] = [];
        $this->assertEmpty($this->configuration->pdf());
    }


    public function testDatabaseConfigInvalidIfHostnameIsMissing()
    {
        $GLOBALS['horncfg']['dbhost'] = null;
        $this->assertFalse($this->configuration->isValidDatabaseConfig());
    }

    public function testDatabaseConfigInvalidIfDatabaseNameIsMissing()
    {
        $GLOBALS['horncfg']['dbname'] = null;
        $this->assertFalse($this->configuration->isValidDatabaseConfig());
    }

    public function testDatabaseConfigInvalidIfUsernameIsMissing()
    {
        $GLOBALS['horncfg']['dbuser'] = null;
        $this->assertFalse($this->configuration->isValidDatabaseConfig());
    }

    public function testDatabaseConfigInvalidIfPasswordIsMissing()
    {
        $GLOBALS['horncfg']['dbpassword'] = null;
        $this->assertFalse($this->configuration->isValidDatabaseConfig());
    }

    public function testDatabaseConfigValidIfAllParametersAreFoundInTheConfigurationFileAndSetCorrectly()
    {
        $GLOBALS['horncfg']['dbpassword'] = 'dbpasswordNE';
        $GLOBALS['horncfg']['dbuser'] = 'dbuserNE';
        $GLOBALS['horncfg']['dbname'] = 'dbnameNE';
        $GLOBALS['horncfg']['dbhost'] = 'dbhostNE';

        $this->assertTrue($this->configuration->isValidDatabaseConfig());
        $this->assertEquals('dbhostNE', $this->configuration->dbhost());
        $this->assertEquals('dbnameNE', $this->configuration->dbname());
        $this->assertEquals('dbuserNE', $this->configuration->dbuser());
        $this->assertEquals('dbpasswordNE', $this->configuration->dbpassword());
    }

}