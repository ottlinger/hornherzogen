<?php

use hornherzogen\admin\FlexibilityMailGenerator;
use hornherzogen\Applicant;
use PHPUnit\Framework\TestCase;

class FlexibilityMailGeneratorTest extends TestCase
{
    private $generator = null;
    private $applicant = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $_GET['lang'] = 'en';
        $this->applicant = new Applicant();
        $this->applicant->setFirstname('Seymor Butt');
        $this->generator = new FlexibilityMailGenerator($this->applicant);
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        $this->generator = null;
        $this->applicant = null;
    }

    /**
     * Test instance of.
     *
     * @test
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf('hornherzogen\admin\FlexibilityMailGenerator', $this->generator);
    }

    public function testBodyRetrievalWeekOne()
    {
        $this->applicant->setWeek(1);
        $this->assertEquals('Hi Seymor Butt, you chose week 1 for Herzogenhorn. This week is overbooked. Would you mind considering to switch to week 2? We are looking forward to your reply in order to complete the booking of all weeks. Thanks in advance, cheers from Berlin, Philipp and Benjamin', $this->generator->getBody());
    }

    public function testBodyRetrievalWeekTwo()
    {
        $this->applicant->setWeek(2);
        $this->assertEquals('Hi Seymor Butt, you chose week 2 for Herzogenhorn. This week is overbooked. Would you mind considering to switch to week 1? We are looking forward to your reply in order to complete the booking of all weeks. Thanks in advance, cheers from Berlin, Philipp and Benjamin', $this->generator->getBody());
    }

    public function testSubjectRetrieval()
    {
        $this->assertEquals('Application Herzogenhorn '.$GLOBALS['messages']['de']['CONST.YEAR'].' - week change possible?', $this->generator->getSubject());
    }
}
