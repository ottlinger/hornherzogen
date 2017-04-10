<?php
use hornherzogen\chart\ChartHelper;
use PHPUnit\Framework\TestCase;
use hornherzogen\Applicant;

class ChartHelperTest extends TestCase
{
    private $chartHelper = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->chartHelper = new ChartHelper();
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        $this->chartHelper = null;
    }

    /**
     * Test instance of.
     *
     * @test
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf('hornherzogen\chart\ChartHelper', $this->chartHelper);
    }

    public function testGetDataRetrievalByCountry()
    {
        $week = "My Week";
        $json = $this->chartHelper->getByCountry($week);
        $this->assertNotNull($json);
        $this->assertContains($week, $json);
    }

    public function testGetDataRetrievalByGender()
    {
        $week = "My Week";
        $json = $this->chartHelper->getByGender($week);
        $this->assertNotNull($json);
        $this->assertContains($week, $json);
    }

    public function testGetCountByGender()
    {
        $json = $this->chartHelper->getCountByWeek();
        $this->assertNotNull($json);
        $this->assertEquals(0, $json);
    }

    public function testSplitByGenderWithNoInputGiven()
    {
        $applicants = array();
        $splitted = ChartHelper::splitByGender($applicants);
        $this->assertNotNull($splitted);
        $this->assertEquals(3, sizeof($splitted));
        $this->assertEmpty($splitted['male']);
        $this->assertEmpty($splitted['female']);
        $this->assertEmpty($splitted['other']);
    }

    public function testSplitByGenderWithValidInput()
    {
        $applicants = array();
        $male = new Applicant();
        $male->setGender('male');
        $female = new Applicant();
        $female->setGender('female');
        $other = new Applicant();
        $other->setGender('other');

        $splitted = ChartHelper::splitByGender($applicants);
        $this->assertNotNull($splitted);
        $this->assertEquals(3, sizeof($splitted));
        $this->assertContainsOnly($male, $splitted['male']);
        $this->assertContainsOnly($female, $splitted['female']);
        $this->assertContainsOnly($other, $splitted['other']);
    }

    public function testConversionToJsonFromDatabaseQuery()
    {
        $countries = array();
        $countries[] = array('country' => "ANY", 'ccount' => 42);
        $countries[] = array('country' => "THING", 'ccount' => 87);

        $json = ChartHelper::toJSON($countries);
        $this->assertNotNull($json);
        $this->assertContains("\"v\":\"ANY\"", $json);
        $this->assertContains("\"v\":42", $json);
        $this->assertContains("\"v\":\"THING\"", $json);
        $this->assertContains("\"v\":87", $json);
    }


}
