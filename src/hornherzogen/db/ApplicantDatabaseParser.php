<?php
namespace hornherzogen\db;


class ApplicantDatabaseParser
{
    private $values;
    private $sql;
    private $applicant;

    function __construct($applicant)
    {
        $this->applicant = $applicant;
        $this->values = array();
        $this->prepare();
    }

    private function prepare()
    {
        $this->parseValues();

        $this->sql = "INSERT INTO applicants(".$this->flatFormat().") VALUES (" . $this->valuesToQuestionMarks() . ")";
        // e.g. "INSERT INTO testtable(name, lastname, age)  VALUES(?,?,?)"
        // TODO handle non-optional fields and empty values in separate class ApplicantParser: input $applicant, return value array(fields => (foo,faa), values => ('foo','faa')
        // use emptyToNull() on self
        // and fill internal state
    }

    private function valuesToQuestionMarks()
    {
        // e.g (?) / (?,?) if sizeof($values)==1 / 2
    }

    private function flatFormat() {
        // flat list into (gender,....) from $values
    }

    public function emptyToNull($input)
    {
        if (isset($input) && strlen(trim($input))) {
            return trim($input);
        }
        return NULL;
    }

    public function getInsertIntoSql()
    {
        return $this->sql;
    }

    public function getInsertIntoValues()
    {
        return $this->values;
    }

    private function parseValues()
    {
        // analyze existence of all fields in strict order and fill array with fields needed
        // if(app-isset(gender) $values[]='gender'.
    }

}