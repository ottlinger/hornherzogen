<?php
declare(strict_types=1);

namespace hornherzogen\chart;

use hornherzogen\db\ApplicantDatabaseWriter;

class ChartHelper
{
    private $applicants;

    function __construct()
    {
        $this->applicants = new ApplicantDatabaseWriter();
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
                {\"c\":[{\"v\":\"Male in week ".$week."\",\"f\":null},{\"v\":".sizeof($applicants['male']).",\"f\":null}]},
                {\"c\":[{\"v\":\"Female in week ".$week."\",\"f\":null},{\"v\":".sizeof($applicants['female']).",\"f\":null}]},
                {\"c\":[{\"v\":\"Others in week ".$week."\",\"f\":null},{\"v\":".sizeof($applicants['other']).",\"f\":null}]}
              ]
        }";
    }

    public function getByCountry($week = NULL)
    {
        // TODO write backend function to group by country
        // each entry will generate a row
        //$applicants = $this->applicants->getAllByWeek($week);

        return "{
          \"cols\": [
                {\"id\":\"\",\"label\":\"Topping\",\"pattern\":\"\",\"type\":\"string\"},
                {\"id\":\"\",\"label\":\"Slices\",\"pattern\":\"\",\"type\":\"number\"}
              ],
          \"rows\": [
                {\"c\":[{\"v\":\"DE\",\"f\":null},{\"v\":23,\"f\":null}]},
                {\"c\":[{\"v\":\"JP\",\"f\":null},{\"v\":2,\"f\":null}]},
                {\"c\":[{\"v\":\"DK\",\"f\":null},{\"v\":5,\"f\":null}]}
              ]
        }";
    }

    // TODO extract into ApplicantDataSplitter class
    public static function splitByGender($applicantList)
    {
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

}