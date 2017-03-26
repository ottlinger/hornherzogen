<?php

namespace hornherzogen\db;


class ApplicantDatabaseReader extends BaseDatabaseWriter
{
    public function listByFoodCategoryPerWeek($week)
    {
        return NULL;
    }

    public function listByRoomCategoryPerWeek($week)
    {
        // array of array
        // 1 -> all single rooms
        // 2 -> all double rooms
        // 3 -> all triple rooms
        // 4 -> all other rooms
        return array(
            '1' => array(),
            '2' => array(),
            '3' => array(),
            '4' => array(),
        );
    }

}