<?php

declare(strict_types=1);
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

    public function testIsAdminWithMultipleConfiguredSuperusers()
    {
        $GLOBALS['horncfg']['superuser'] = 'emil,walter,bernhard';
        $_SERVER['PHP_AUTH_USER'] = 'emil';
        $this->assertTrue($this->adminHelper->isAdmin());
    }

    public function testIsAdminWithSingleConfiguredSuperusers()
    {
        $GLOBALS['horncfg']['superuser'] = 'emil';
        $_SERVER['PHP_AUTH_USER'] = 'emil';
        $this->assertTrue($this->adminHelper->isAdmin());
    }

    public function testIsAdminWithMultipleConfiguredSuperusersNegativeTestcase()
    {
        $GLOBALS['horncfg']['superuser'] = 'emil,limel';
        $_SERVER['PHP_AUTH_USER'] = 'walter';
        $this->assertFalse($this->adminHelper->isAdmin());
    }

    public function testIsAdminWithSingleConfiguredSuperusersNegativeTestcase()
    {
        $GLOBALS['horncfg']['superuser'] = 'emil';
        $_SERVER['PHP_AUTH_USER'] = 'walter';
        $this->assertFalse($this->adminHelper->isAdmin());
    }

    public function testIsAdminWithoutConfigurationKey()
    {
        $GLOBALS['horncfg']['superuser'] = null;
        $_SERVER['PHP_AUTH_USER'] = null;
        $this->assertFalse($this->adminHelper->isAdmin());
    }

    public function testIsAdminWithConfigurationKeyAndConfiguredUser()
    {
        $user = 'womanchu';
        $GLOBALS['horncfg']['superuser'] = $user;
        $_SERVER['PHP_AUTH_USER'] = $user;
        $this->assertTrue($this->adminHelper->isAdmin());
    }

    public function testUsernameIsNoneIfNotLoggedIn()
    {
        $_SERVER['PHP_AUTH_USER'] = null;
        $this->assertEquals('none', $this->adminHelper->getUserName());
    }

    public function testShowUserLoggedInNoSuperUser()
    {
        $_SERVER['PHP_AUTH_USER'] = null;
        $this->assertEquals('<span class="glyphicon glyphicon-user"></span> none@nohost</a>', $this->adminHelper->showUserLoggedIn());
        $this->assertEquals('<li><a href="#"><span class="glyphicon glyphicon-lamp"></span> Not logged in</a></li>', $this->adminHelper->showLogoutMenu());
    }

    public function testShowUserLoggedInSuperUser()
    {
        $user = 'womanchu';
        $GLOBALS['horncfg']['superuser'] = $user;
        $_SERVER['PHP_AUTH_USER'] = $user;
        $this->assertEquals('<span class="glyphicon glyphicon-user" style="color: red;"></span> womanchu@nohost</a>', $this->adminHelper->showUserLoggedIn());
        $this->assertEquals('<li><a href="./logout.php"><span class="glyphicon glyphicon-erase"></span> Logout</a></li>', $this->adminHelper->showLogoutMenu());
    }

    public function testShowSuperUserMenuNotYet()
    {
        $this->assertContains('<span class="glyphicon glyphicon-road"></span> Superadmin-Menu<span', $this->adminHelper->showSuperUserMenu());
    }

    public function testNoShowSuperUserMenu()
    {
        $GLOBALS['horncfg']['superuser'] = '';
        $_SERVER['PHP_AUTH_USER'] = 'noAdminUser';

        $this->assertEquals('<li><a href="#"><span class="glyphicon glyphicon-road"></span>No Superadmin-Menu</a></li>', $this->adminHelper->showSuperUserMenu());
    }

    public function testExtractPageUri_http()
    {
        $_SERVER['SERVER_NAME'] = 'myhorst';
        $_SERVER['REQUEST_URI'] = 'myreq';

        $this->assertEquals('http://myhorstmyreq', $this->adminHelper->thisPageUrl());
    }

    public function testExtractPageUri_https()
    {
        $_SERVER['HTTPS'] = 'on';
        $_SERVER['SERVER_NAME'] = 'myhorst';
        $_SERVER['REQUEST_URI'] = 'myreq';

        $this->assertEquals('https://myhorstmyreq', $this->adminHelper->thisPageUrl());
    }

    public function testExtractPageUri_differentPort()
    {
        $_SERVER['HTTPS'] = 'on';
        $_SERVER['SERVER_NAME'] = 'myhorst';
        $_SERVER['REQUEST_URI'] = '/myreq';
        $_SERVER['SERVER_PORT'] = '4711';

        $this->assertEquals('https://myhorst:4711/myreq', $this->adminHelper->thisPageUrl());
    }

    public function testGetHostFallbackToNull()
    {
        $_SERVER['SERVER_NAME'] = null;
        $this->assertEquals('nohost', $this->adminHelper->getHost());

        $_SERVER['SERVER_NAME'] = '';
        $this->assertEquals('nohost', $this->adminHelper->getHost());
    }
}
