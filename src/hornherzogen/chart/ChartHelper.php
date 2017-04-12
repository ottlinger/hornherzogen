<?php
declare(strict_types=1);

namespace hornherzogen\chart;

use hornherzogen\db\ApplicantDatabaseReader;

class ChartHelper
{
    private $reader;

    function __construct()
    {
        $this->reader = new ApplicantDatabaseReader();
    }

    public function getByGender($week = NULL)
    {
        $applicants = $this->splitByGender($this->reader->getAllByWeek($week));

        $headers = self::calculateTitleForGender($week);

        return "{
          \"cols\": [
                {\"id\":\"\",\"label\":\"Gender \",\"pattern\":\"\",\"type\":\"string\"},
                {\"id\":\"\",\"label\":\"Slices \",\"pattern\":\"\",\"type\":\"number\"}
              ],
          \"rows\": [
                {\"c\":[{\"v\":\"" . $headers['female'] . "\",\"f\":null},{\"v\":" . sizeof($applicants['female']) . ",\"f\":null}]},
                {\"c\":[{\"v\":\"" . $headers['male'] . "\",\"f\":null},{\"v\":" . sizeof($applicants['male']) . ",\"f\":null}]},
                {\"c\":[{\"v\":\"" . $headers['other'] . "\",\"f\":null},{\"v\":" . sizeof($applicants['other']) . ",\"f\":null}]}
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

        foreach ($applicantList as $applicant) {
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
        }

        return $results;
    }

    public static function calculateTitleForGender($week)
    {
        if (isset($week) && strlen($week)) {
            return array(
                'female' => "Frauen in Woche " . $week,
                'male' => "Männer in Woche " . $week,
                'other' => "Andere in Woche " . $week
            );
        }

        return array(
            'female' => "Frauen",
            'male' => "Männer",
            'other' => "Andere"
        );
    }

    public function getCountByWeek($week = NULL)
    {
        return sizeof($this->reader->getAllByWeek($week));
    }

    public function getByCountry($week = NULL)
    {
        $bycountry = $this->reader->groupByOriginByWeek($week);

        return "{
          \"cols\": [
                {\"id\":\"\",\"label\":\"" . self::calculateTitleByCountry($week) . "\",\"pattern\":\"\",\"type\":\"string\"},
                {\"id\":\"\",\"label\":\"Slices\",\"pattern\":\"\",\"type\":\"number\"}
              ],
          \"rows\": [" . $this->toJSON($bycountry) . "]
        }";
    }

    public static function calculateTitleByCountry($week)
    {
        if (isset($week) && strlen($week)) {
            return "Countries in week " . $week;
        }
        return "Countries";
    }

    public static function toJSON($countryEntries)
    {
        if (!isset($countryEntries)) {
            return "{\"c\":[{\"v\":\"DE\",\"f\":null},{\"v\":23,\"f\":null}]},{\"c\":[{\"v\":\"JP\",\"f\":null},{\"v\":2,\"f\":null}]},{\"c\":[{\"v\":\"DK\",\"f\":null},{\"v\":5,\"f\":null}]}";
        }

        $json = "";

        foreach ($countryEntries as $country) {
            $json .= "{\"c\":[{\"v\":\"" . $country['country'] . "\",\"f\":null},{\"v\":" . $country['ccount'] . ",\"f\":null}]},";
        }

        return rtrim($json, ",");

    }

}