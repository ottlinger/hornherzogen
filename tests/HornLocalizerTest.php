<?php
use PHPUnit\Framework\TestCase;

class HornLocalizerTest extends TestCase
{
    private $language = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->language = new hornherzogen\HornLocalizer();
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        $this->language = null;
    }

    /**
     * Test instance of.
     *
     * @test
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf('hornherzogen\HornLocalizer', $this->language);
    }

    /**
     * Test fallback 'de' in case of no get parameters and nothing in session.
     *
     * @test
     */
    public function testFallbackToGermanAndStateIsStoredInSessionIfNoUrlParameterIsGiven()
    {
        $_GET['lang'] = null;
        $this->assertEquals('de', \hornherzogen\HornLocalizer::getLanguage());
        self::assertTrue(isset($_SESSION));
        self::assertTrue(isset($_SESSION['language']));
        $this->assertEquals('de', $_SESSION['language']);
    }

    /**
     * Test setting the language to an invalid language not currently supported.
     *
     * @test
     */
    public function testFallbackToGermanIfNoProperLanuageCodeIsGiven()
    {
        $_GET['lang'] = null;
        $_SESSION['language'] = 'floh';
        $this->assertEquals('de', \hornherzogen\HornLocalizer::getLanguage());
        self::assertTrue(isset($_SESSION));
        self::assertTrue(isset($_SESSION['language']));
        $this->assertEquals('de', $_SESSION['language']);
    }

    /**
     * Test reloading language state from current session.
     *
     * @test
     */
    public function testReloadExistingLanguageFromSessionWithoutUrlParameter()
    {
        $_GET['lang'] = null;
        $_SESSION['language'] = 'ru';
        $this->assertEquals('ru', \hornherzogen\HornLocalizer::getLanguage());
        self::assertTrue(isset($_SESSION));
        self::assertTrue(isset($_SESSION['language']));
        $this->assertEquals('ru', $_SESSION['language']);
    }

    /**
     * Test reloading language state from current session.
     *
     * @test
     */
    public function testOverloadingExistingLanguageFromSessionWithUrlParameter()
    {
        // case: url param given with preset state in session
        $_GET['lang'] = 'en';
        $_SESSION['language'] = 'ru';
        $this->assertEquals('en', \hornherzogen\HornLocalizer::getLanguage());
        self::assertTrue(isset($_SESSION));
        self::assertTrue(isset($_SESSION['language']));
        $this->assertEquals('en', $_SESSION['language']);

        // link is clicked without session, thus fallback to session
        $_SESSION['language'] = 'en';
        $this->assertEquals('en', \hornherzogen\HornLocalizer::getLanguage());
        self::assertTrue(isset($_SESSION));
        self::assertTrue(isset($_SESSION['language']));
        $this->assertEquals('en', $_SESSION['language']);

    }

}