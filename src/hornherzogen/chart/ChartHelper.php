<?php
declare(strict_types=1);

namespace hornherzogen\chart;

use hornherzogen\db\ApplicantDatabaseReader;
use hornherzogen\db\ApplicantDatabaseWriter;

class ChartHelper
{
    private $applicants;
    private $reader;

    function __construct()
    {
        $this->applicants = new ApplicantDatabaseWriter();
        $this->reader = new ApplicantDatabaseReader();
    }

    public function getByGender($week = NULL)
    {
        $applicants = $this->splitByGender($this->applicants->getAllByWeek($week));

        return "{
          \"cols\": [
                {\"id\":\"\",\"label\":\"Gender \",\"pattern\":\"\",\"type\":\"string\"},
                {\"id\":\"\",\"label\":\"Slices \",\"pattern\":\"\",\"type\":\"number\"}
              ],
          \"rows\": [
                {\"c\":[{\"v\":\"Male in week " . $week . "\",\"f\":null},{\"v\":" . sizeof($applicants['male']) . ",\"f\":null}]},
                {\"c\":[{\"v\":\"Female in week " . $week . "\",\"f\":null},{\"v\":" . sizeof($applicants['female']) . ",\"f\":null}]},
                {\"c\":[{\"v\":\"Others in week " . $week . "\",\"f\":null},{\"v\":" . sizeof($applicants['other']) . ",\"f\":null}]}
              ]
        }";
    }

    public static function splitByGender($applicantList)
    {
        // TODO extract into ApplicantDataSplitter class
        $results = array(
            'other' => array(),
            'male' => array(),
            'female' => array(),
        );

        foreach ($applicantList as $applicant)
            switch ($applicant->getGender()) {

                case "female":
                    $results['female'][] = $applicant;
                    break;
                case "male":
                    $results['male'][] = $applicant;
                    break;

                default:
                    $results['other'][] = $applicant;
                    break;
            }
        return $results;
    }

    public function getCountByWeek($week = NULL)
    {
        return sizeof($this->applicants->getAllByWeek($week));
    }

    public function getByCountry($week = NULL)
    {
        $bycountry = $this->reader->groupByOriginByWeek($week);

        return "{
          \"cols\": [
                {\"id\":\"\",\"label\":\"Countries in week " . $week . "\",\"pattern\":\"\",\"type\":\"string\"},
                {\"id\":\"\",\"label\":\"Slices\",\"pattern\":\"\",\"type\":\"number\"}
              ],
          \"rows\": [" . $this->toJSON($bycountry) . "]
        }";
    }

    public static function toJSON($countryEntries)
    {
        if (!isset($countryEntries)) {
            return "
                {\"c\":[{\"v\":\"DE\",\"f\":null},{\"v\":23,\"f\":null}]},
                {\"c\":[{\"v\":\"JP\",\"f\":null},{\"v\":2,\"f\":null}]},
                {\"c\":[{\"v\":\"DK\",\"f\":null},{\"v\":5,\"f\":null}]}
            ";
        }

        $json = "";

        foreach ($countryEntries as $country) {
            $json .= "{\"c\":[{\"v\":\"" . $country['country'] . "\",\"f\":null},{\"v\":" . $country['ccount'] . ",\"f\":null}]},";
        }

        return rtrim($json, ",");

    }

}