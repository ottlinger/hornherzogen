<?php

use PHPUnit\Framework\TestCase;

// Included automatically via phpunit.xml
//include_once '../inc/config.php';
//include_once '../inc/session.php';
//include_once '../inc/localization.php';

class IncludeFilesTest extends TestCase
{
    public function testConfigurationHasBeenRead()
    {
        $this->assertNotEmpty($GLOBALS['horncfg']);
    }

    public function testLocalizationIsInitialized()
    {
        $this->assertNotEmpty($GLOBALS['messages']);
    }

    public function testSessionIsStarted()
    {
        $this->assertEquals(PHP_SESSION_ACTIVE, session_status());
    }
}
