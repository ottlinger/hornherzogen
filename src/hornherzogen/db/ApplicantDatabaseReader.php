<?php
declare(strict_types=1);

namespace hornherzogen\db;

class ApplicantDatabaseReader extends BaseDatabaseWriter
{
    // implemented by #66
    public function listByFoodCategoryPerWeek($week)
    {
        if ($this->isHealthy()) {

        }
        return array();
    }

    public function listByRoomCategoryPerWeek($week)
    {
        // array of array
        // 1 -> all single rooms
        // 2 -> all double rooms
        // 3 -> all triple rooms
        // 4 -> all other rooms
        $results = array(
            '1' => array(),
            '2' => array(),
            '3' => array(),
            '4' => array(),
        );

        if ($this->isHealthy()) {

        }

        return $results;
    }

}