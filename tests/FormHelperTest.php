<?php
use hornherzogen\FormHelper;
use PHPUnit\Framework\TestCase;

class FormHelperTest extends TestCase
{
    private $formHelper = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->formHelper = new FormHelper;
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        $this->formHelper = null;
    }

    /**
     * Test instance of $this->formHelper
     *
     * @test
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf('hornherzogen\FormHelper', $this->formHelper);
    }

    public function testFilterOutHtml()
    {
        $dataIn = ' <html/>    ';
        $this->assertEquals('&lt;html/&gt;', $this->formHelper->filterUserInput($dataIn));
    }

    public function testFilterOutDoesNotChangeContestsItself()
    {
        $dataIn = ' html    ';
        $this->assertEquals('html', $this->formHelper->filterUserInput($dataIn));
    }

    public function testTrimmingAndCuttingWithNullDataAndLength()
    {
        $dataIn = NULL;
        $this->assertNull($this->formHelper->trimAndCutAfter($dataIn, 4711));
    }

    public function testTrimmingAndCuttingWithNullDataAndNullLength()
    {
        $this->assertNull($this->formHelper->trimAndCutAfter(NULL, NULL));
    }

    public function testTrimmingAndCuttingWithDataThatNeedsTrimmingButNoCutting()
    {
        $length = 10;
        $input = str_repeat("b", $length);
        $this->assertEquals($input, $this->formHelper->trimAndCutAfter('     ' . $input . '        ', $length));
    }

    public function testTrimmingAndCuttingWithDataThatNeedsTrimmingAndCutting()
    {
        $length = 10;
        $input = str_repeat("b", $length);
        $this->assertEquals($input, $this->formHelper->trimAndCutAfter('     ' . $input . 'aaaaa        ', $length));
    }
}