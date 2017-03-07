<?php
namespace hornherzogen\db;


class ApplicantDatabaseParser
{
    private $values;

    public function emptyToNull($input) {
        if(isset($input) && strlen(trim($input))) {
            return trim($input);
        }
        return NULL;
    }


}