<?php
use hornherzogen\db\DatabaseHelper;
use PHPUnit\Framework\TestCase;

class DatabaseHelperTest extends TestCase
{
    private $helper = null;
    private $pdo = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->helper = new DatabaseHelper();
        $this->pdo = new PDO('sqlite::memory:');
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        $this->helper = null;
        $this->pdo = null;
    }

    /**
     * Test instance of.
     *
     * @test
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf('hornherzogen\db\DatabaseHelper', $this->helper);
    }

    public function testEmptyToNullWithNullArgument()
    {
        $this->assertNull($this->helper->emptyToNull(NULL));
    }

    public function testEmptyToNullWithNonNullArgument()
    {
        $this->assertEquals("asd dsa", $this->helper->emptyToNull("  asd dsa "));
    }

    public function testTrimAndMaskNull()
    {
        $this->assertNull($this->helper->trimAndMask(NULL));
    }

    public function testPreventSQLInjectionWithParameterNull()
    {
        $this->assertNull($this->helper->makeSQLCapable(NULL, NULL));
    }

    public function testPreventSQLInjectionWithParameterGiven()
    {
        $this->assertEquals("'no change needed'", $this->helper->makeSQLCapable("no change needed", $this->pdo));
    }

    public function testPreventSQLInjectionWithSqlInParameterGiven()
    {
        $this->assertEquals("' \%sdasd \_ff\_'", $this->helper->makeSQLCapable(" %sdasd _ff_", $this->pdo));
    }

    public function testPreventSQLInjectionWithParameterGivenWithoutDatabaseConnection()
    {
        $this->assertEquals("'no change needed'", $this->helper->makeSQLCapable("no change needed", NULL));
    }

    public function testPreventSQLInjectionWithSqlInParameterGivenWithoutDatabaseConnection()
    {
        $this->assertEquals("' \%sdasd \_ff\_'", $this->helper->makeSQLCapable(" %sdasd _ff_", NULL));
    }

}