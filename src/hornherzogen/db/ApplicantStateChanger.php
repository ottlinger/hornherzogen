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

        return TRUE;
    }

    public function mapToDatabaseDateField($stateId)
    {
        $state = $this->statusReader->getById($stateId);

        switch ($state['name']) {
            case 'WAITING_FOR_PAYMENT':
                return array('mail' => 'PaymentMailer', 'field' => 'paymentRequested');

            case 'PAID':
                return array('field' => 'paymentReceived');

            case 'APPLIED':
                return array('field' => 'created');

            case 'REGISTERED':
            case 'CONFIRMED':

            case 'SPAM':
            case 'REJECTED':
            case 'CANCELLED':
                return array('field' => 'cancelled');
            default:
                return array('field' => 'cancelled');
        }


    }


}