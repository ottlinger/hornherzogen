<?php
use hornherzogen\HornLocalizer;
use PHPUnit\Framework\TestCase;

class HornLocalizerTest extends TestCase
{
    private $language = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $_GET['lang'] = "de"; // set to fallback language
        $this->language = new HornLocalizer();
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        $_GET['lang'] = null;
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
     * Test fallback language in case of no get parameters and nothing in session.
     *
     * @test
     */
    public function testFallbackToGermanAndStateIsStoredInSessionIfNoUrlParameterIsGiven()
    {
        $_GET['lang'] = null;
        $this->assertEquals('de', HornLocalizer::getLanguage());
        self::assertTrue(isset($_SESSION));
        self::assertTrue(isset($_SESSION['language']));
        $this->assertEquals('de', $_SESSION['language']);
    }

    /**
     * Test setting the language to an invalid language not currently supported.
     *
     * @test
     */
    public function testFallbackToGermanIfNoProperLanguageCodeIsGivenInSession()
    {
        $_GET['lang'] = null;
        $_SESSION['language'] = 'bogusNotSupportedHere';
        $this->assertEquals('de', HornLocalizer::getLanguage());
        self::assertTrue(isset($_SESSION));
        self::assertTrue(isset($_SESSION['language']));
        $this->assertEquals('de', $_SESSION['language']);
    }

    /**
     * Test setting the language to an invalid language not currently supported.
     *
     * @test
     */
    public function testFallbackToGermanIfNoProperLanguageCodeIsGivenInUrlParameter()
    {
        $_GET['lang'] = 'bogusNotSupportedHere';
        $_SESSION['language'] = null;
        $this->assertEquals('de', HornLocalizer::getLanguage());
        self::assertTrue(isset($_SESSION));
        self::assertTrue(isset($_SESSION['language']));
        $this->assertEquals('de', $_SESSION['language']);
    }

    /**
     * Test setting the language to an invalid language not currently supported.
     *
     * @test
     */
    public function testFallbackToGermanIfNoProperLanguageCodeIsGivenInUrlParameterAndSession()
    {
        $_GET['lang'] = 'bogusNotSupportedHere';
        $_SESSION['language'] = 'bogusNotSupportedHere';
        $this->assertEquals('de', HornLocalizer::getLanguage());
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
        $this->assertEquals('ru', HornLocalizer::getLanguage());
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
        $this->assertEquals('en', HornLocalizer::getLanguage());
        self::assertTrue(isset($_SESSION));
        self::assertTrue(isset($_SESSION['language']));
        $this->assertEquals('en', $_SESSION['language']);

        // link is clicked without session, thus fallback to session
        $_SESSION['language'] = 'en';
        $this->assertEquals('en', HornLocalizer::getLanguage());
        self::assertTrue(isset($_SESSION));
        self::assertTrue(isset($_SESSION['language']));
        $this->assertEquals('en', $_SESSION['language']);
    }

    public function testSessionDataRetrieval()
    {
        $_SESSION = null;
        self::assertEmpty(HornLocalizer::getLanguageFromSession());

        $_SESSION = array();
        $_SESSION['language'] = null;
        self::assertEmpty(HornLocalizer::getLanguageFromSession());

        $_SESSION['language'] = ' дняtrimMeProper今日lyC         ';
        self::assertEquals('дняtrimMeProper今日lyC', HornLocalizer::getLanguageFromSession());
    }

    public function testUrlParameterDataRetrieval()
    {
        $_GET = null;
        self::assertEmpty(HornLocalizer::getLanguageFromUrlParameter());

        $_GET = array();
        $_GET['lang'] = null;
        self::assertEmpty(HornLocalizer::getLanguageFromUrlParameter());

        $_GET['lang'] = ' дняtrimMeProper今日lyC         ';
        self::assertEquals('дняtrimMeProper今日lyC', HornLocalizer::getLanguageFromUrlParameter());
    }

    public function testLocalizationKeyRetrievalWithUnknownKeyAndNoParams()
    {
        self::assertEquals('Unknown key: "unknownI18NKey"', $this->language->i18n('unknownI18NKey'));
    }

    public function testLocalizationKeyRetrievalWithUnknownKeyAndParams()
    {
        self::assertEquals('Unknown key: "unknownI18NKey"', $this->language->i18nParams('unknownI18NKey', 'just', 'a', 'key'));
    }

    public function testLocalizationKeyRetrievalWithKnownKeyAndNoParams()
    {
        self::assertEquals('Herzogenhorn '.$this->language->i18n('CONST.YEAR').' - Anmeldung', $this->language->i18n('FORM.TITLE'));
    }

    public function testLocalizationKeyRetrievalWithKnownKeyAndParams()
    {
        self::assertEquals('Es ist just', $this->language->i18nParams('TIME', 'just', 'a', 'key'));
    }

    public function testFallbackToGermanForUnknownRussianKey() {
        // WHEN language is set to ru
        $_GET['lang'] = "ru";
        // German key is returned for unknown Russian one
        self::assertEquals("Alle mit Stern (*) markierten Felder sind Pflichtfelder und müssen angegeben werden.", $this->language->i18n("FORM.MANDATORYFIELDS"));
    }
}