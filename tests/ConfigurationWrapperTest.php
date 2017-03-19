<?php
use hornherzogen\ConfigurationWrapper;
use PHPUnit\Framework\TestCase;

class ConfigurationWrapperTest extends TestCase
{
    private $configuration = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->configuration = new ConfigurationWrapper;
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
     * Return debug value from config.
     *
     * @test
     */
    public function testDebugModeIsReturnedProperlyFromConfig()
    {
        $GLOBALS['horncfg']['debug'] = true;
        $this->assertEquals(1, $this->configuration->debug());
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

    public function testFilterBooleanMethodsTrueCase()
    {
        $GLOBALS["horncfg"]["sendinternalregistrationmails"] = true;
        $GLOBALS["horncfg"]["sendregistrationmails"] = true;

        $this->assertTrue($this->configuration->sendregistrationmails());
        $this->assertTrue($this->configuration->sendinternalregistrationmails());
    }

    public function testFilterBooleanMethodsFalseCase()
    {
        $GLOBALS["horncfg"]["sendinternalregistrationmails"] = false;
        $GLOBALS["horncfg"]["sendregistrationmails"] = false;

        $this->assertFalse($this->configuration->sendregistrationmails());
        $this->assertFalse($this->configuration->sendinternalregistrationmails());
    }

    public function testFilterBooleanMethodsNullCase()
    {
        $GLOBALS["horncfg"]["sendinternalregistrationmails"] = NULL;
        $GLOBALS["horncfg"]["sendregistrationmails"] = NULL;

        $this->assertFalse($this->configuration->sendregistrationmails());
        $this->assertFalse($this->configuration->sendinternalregistrationmails());

        $GLOBALS["horncfg"] = NULL;
        $this->assertFalse($this->configuration->sendregistrationmails());
        $this->assertFalse($this->configuration->sendinternalregistrationmails());
    }

    public function testFilterBooleanMethodsRandomStringMapsToTrue()
    {
        $GLOBALS["horncfg"]["sendinternalregistrationmails"] = 'thisIsNotABoolean';
        $GLOBALS["horncfg"]["sendregistrationmails"] = 'thisIsNotABoolean';

        $this->assertTrue($this->configuration->sendregistrationmails());
        $this->assertTrue($this->configuration->sendinternalregistrationmails());
    }

    public function testConfigurationAttributeSubmissionEndIsSet()
    {
        $GLOBALS["horncfg"]["submissionend"] = '2017-01-01';
        $this->assertEquals("2017-01-01", $this->configuration->submissionend());
    }

    public function testMaskWithAsteriskShitInShitOut()
    {
        $this->assertNull($this->configuration->maskWithAsterisk(NULL));
        $this->assertNull($this->configuration->maskWithAsterisk(NULL, 47));
    }

    public function testMaskWithAsteriskRegularUsage()
    {
        $this->assertEquals("***", $this->configuration->maskWithAsterisk("abc"));
        $this->assertEquals("*", $this->configuration->maskWithAsterisk("a"));
        $this->assertEquals("*", $this->configuration->maskWithAsterisk(" "));
        $this->assertEquals("", $this->configuration->maskWithAsterisk(""));
    }

    public function testMaskWithAsteriskRegularUsageWithPartialMasking()
    {
        $this->assertEquals("a**", $this->configuration->maskWithAsterisk("abc", 1));
        $this->assertEquals("a", $this->configuration->maskWithAsterisk("a", 1));
        $this->assertEquals("bd**", $this->configuration->maskWithAsterisk("bdef", 2));
    }

    public function testToStringWithoutDefaultPasswordsFromConfigurationTemplate()
    {
        $this->assertStringStartsWith("<pre>Current configuration is: ", $this->configuration->__toString());
        $this->assertNotContains("pi2ieVid1234567", $this->configuration->__toString());
        $this->assertNotContains("pi2************", $this->configuration->__toString());
    }
}