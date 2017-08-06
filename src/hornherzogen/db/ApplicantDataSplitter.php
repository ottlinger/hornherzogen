<?php

declare(strict_types=1);

namespace hornherzogen\db;

class ApplicantDataSplitter
{
    private $databaseHelper;

    public function __construct()
    {
        $this->databaseHelper = new DatabaseHelper();
    }

    public function splitByRoomCategory($applicantList)
    {
        $results = [
            '1' => [],
            '2' => [],
            '3' => [],
            '4' => [],
        ];

        if (null != $applicantList) {
            foreach ($applicantList as $applicant) {
                switch ($applicant->getRoom()) {
                    case '1bed':
                        $results['1'][] = $applicant;
                        break;

                    case '2bed':
                        $results['2'][] = $applicant;
                        break;

                    case '3bed':
                        $results['3'][] = $applicant;
                        break;

                    default:
                        $results['4'][] = $applicant;
                        break;
                }
            }
        }

        return $results;
    }

    public function splitByGender($applicantList)
    {
        $results = [
            'other'  => [],
            'male'   => [],
            'female' => [],
        ];

        if (null != $applicantList) {
            foreach ($applicantList as $applicant) {
                switch ($applicant->getGender()) {
                    case 'female':
                        $results['female'][] = $applicant;
                        break;
                    case 'male':
                        $results['male'][] = $applicant;
                        break;

                    default:
                        $results['other'][] = $applicant;
                        break;
                }
            }
        }

        return $results;
    }
}
