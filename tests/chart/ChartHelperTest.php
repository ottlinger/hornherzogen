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

    public function testGetDataRetrievalByCountryWithoutWeek()
    {
        $json = $this->chartHelper->getByCountry();
        $this->assertNotNull($json);
        $this->assertContains("Countries", $json);
        $this->assertNotContains("in week", $json);
    }

    public function testGetDataRetrievalByGender()
    {
        $week = "My Week";
        $json = $this->chartHelper->getByGender($week);
        $this->assertNotNull($json);
        $this->assertContains($week, $json);
    }

    public function testGetDataRetrievalByGenderWithoutWeek()
    {
        $json = $this->chartHelper->getByGender();
        $this->assertNotNull($json);
        $this->assertContains("Frauen", $json);
        $this->assertContains("Männer", $json);
        $this->assertContains("Andere", $json);
        $this->assertNotContains("in Woche", $json);
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
        $male = new Applicant();
        $male->setGender('male');
        $female = new Applicant();
        $female->setGender('female');
        $other = new Applicant();
        $other->setGender('other');
        $applicants = array();
        $applicants[] = $male;
        $applicants[] = $female;
        $applicants[] = $other;

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

    public function testConversionToJsonFromDatabaseQueryWithoutEntriesWillReturnDummyResults()
    {
        $json = ChartHelper::toJSON(NULL);
        $this->assertNotNull($json);
        $this->assertEquals("{\"c\":[{\"v\":\"DE\",\"f\":null},{\"v\":23,\"f\":null}]},{\"c\":[{\"v\":\"JP\",\"f\":null},{\"v\":2,\"f\":null}]},{\"c\":[{\"v\":\"DK\",\"f\":null},{\"v\":5,\"f\":null}]}", $json);
    }

}
