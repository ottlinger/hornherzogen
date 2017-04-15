<?php
declare(strict_types=1);
namespace hornherzogen\db;


class ApplicantStateChanger extends BaseDatabaseWriter
{

    /**
     * Change the state of the given applicant to the given state.
     *
     * @param $applicantId
     * @param $stateId
     */
    public function changeStateTo($applicantId, $stateId) {
        // TODO retrieve statename to allow using
        if (!self::isHealthy()) {
            return FALSE;
        }
        return TRUE;
    }

}