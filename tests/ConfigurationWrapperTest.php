<?php

class ConfigurationWrapperTest extends PHPUnit_Framework_TestCase
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
     * Return mail value from config.
     *
     * @test
     */
    public function testMailIsReturnedFromConfigFile()
    {
        $GLOBALS['horncfg']['mail'] = "mymail";
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

}