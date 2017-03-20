<?php
declare(strict_types = 1);
use hornherzogen\AdminHelper;
use PHPUnit\Framework\TestCase;

class AdminHelperTest extends TestCase
{
    private $adminHelper = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->adminHelper = new AdminHelper();
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        $this->adminHelper = null;
    }

    /**
     * Test instance of.
     *
     * @test
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf('hornherzogen\AdminHelper', $this->adminHelper);
    }

    public function testIsAdminWithoutConfigurationKey()
    {
        $GLOBALS["horncfg"]["superuser"] = NULL;
        $_SERVER['PHP_AUTH_USER'] = NULL;
        $this->assertFalse($this->adminHelper->isAdmin());
    }

    public function testIsAdminWithConfigurationKeyAndConfiguredUser()
    {
        $user = "womanchu";
        $GLOBALS["horncfg"]["superuser"] = $user;
        $_SERVER['PHP_AUTH_USER'] = $user;
        $this->assertTrue($this->adminHelper->isAdmin());
    }

}