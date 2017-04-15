<?php
declare(strict_types=1);

namespace hornherzogen\db;

class ApplicantStateChanger extends BaseDatabaseWriter
{

    private $statusReader;

    function __construct($databaseConnection = NULL)
    {
        parent::__construct($databaseConnection);

        $this->statusReader = new StatusDatabaseReader($databaseConnection);


    }

    /**
     * Change the state of the given applicant to the given state.
     *
     * @param $applicantId
     * @param $stateId
     */
    public function changeStateTo($applicantId, $stateId)
    {
        // TODO retrieve statename to allow using
        if (!self::isHealthy()) {
            return FALSE;
        }

        // get state


        return TRUE;
    }




}