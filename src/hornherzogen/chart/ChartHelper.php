<?php
declare(strict_types=1);

namespace hornherzogen\chart;

use hornherzogen\db\ApplicantDatabaseWriter;

class ChartHelper
{
    private $applicants;

    function __construct()
    {
        $applicants = new ApplicantDatabaseWriter();
    }

    public function getByGender()
    {
        return "{
          \"cols\": [
                {\"id\":\"\",\"label\":\"Topping\",\"pattern\":\"\",\"type\":\"string\"},
                {\"id\":\"\",\"label\":\"Slices\",\"pattern\":\"\",\"type\":\"number\"}
              ],
          \"rows\": [
                {\"c\":[{\"v\":\"Mushrooms\",\"f\":null},{\"v\":3,\"f\":null}]},
                {\"c\":[{\"v\":\"Onions\",\"f\":null},{\"v\":1,\"f\":null}]},
                {\"c\":[{\"v\":\"Olives\",\"f\":null},{\"v\":1,\"f\":null}]},
                {\"c\":[{\"v\":\"Zucchini\",\"f\":null},{\"v\":1,\"f\":null}]},
                {\"c\":[{\"v\":\"Pepperoni\",\"f\":null},{\"v\":2,\"f\":null}]}
              ]
        }";
    }

    public function getByWeek($week)
    {

//        $applicants = $this->applicants->getAllByWeek(NULL);

        return "{
          \"cols\": [
                {\"id\":\"\",\"label\":\"Topping " . $week . "\",\"pattern\":\"\",\"type\":\"string\"},
                {\"id\":\"\",\"label\":\"Slices " . $week . "\",\"pattern\":\"\",\"type\":\"number\"}
              ],
          \"rows\": [
                {\"c\":[{\"v\":\"Mushrooms\",\"f\":null},{\"v\":3,\"f\":null}]},
                {\"c\":[{\"v\":\"Onions\",\"f\":null},{\"v\":1,\"f\":null}]},
                {\"c\":[{\"v\":\"Olives\",\"f\":null},{\"v\":1,\"f\":null}]},
                {\"c\":[{\"v\":\"Zucchini\",\"f\":null},{\"v\":1,\"f\":null}]},
                {\"c\":[{\"v\":\"Pepperoni\",\"f\":null},{\"v\":2,\"f\":null}]}
              ]
        }";
    }

    public function splitByGender($applicantList)
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