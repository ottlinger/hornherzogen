<?php
declare(strict_types=1);

namespace hornherzogen\db;

class ApplicantDataSplitter
{
    private $databaseHelper;

    function __construct()
    {
        $this->databaseHelper = new DatabaseHelper();
    }


    public function splitByRoomCategory($applicantsResultFromDatabase)
    {
        $results = array(
            '1' => array(),
            '2' => array(),
            '3' => array(),
            '4' => array(),
        );

        if (NULL != $applicantsResultFromDatabase) {
            foreach ($applicantsResultFromDatabase as $row) {
                $applicant = $this->databaseHelper->fromDatabaseToObject($row);

                switch ($applicant->getRoom()) {
                    case "1bed":
                        $results['1'][] = $applicant;
                        break;

                    case "2bed":
                        $results['2'][] = $applicant;
                        break;

                    case "3bed":
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


}