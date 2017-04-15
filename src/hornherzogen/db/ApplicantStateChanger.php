<?php
declare(strict_types=1);

namespace hornherzogen\db;

use hornherzogen\mail\PaymentMailer;

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
        $result = FALSE;

        if (!self::isHealthy() || NULL === $applicantId || NULL === $stateId) {
            return FALSE;
        }

        $mappingResult = $this->mapToDatabaseDateField($stateId);
        if (!empty($mappingResult)) {

            // TODO perform update in database

            if($this->formHelper->isSetAndNotEmptyInArray($mappingResult, 'mail')) {
                if('PaymentMailer' == $mappingResult['mail']) {
                    $paymentMailer = new PaymentMailer($applicantId);
                    $paymentMailer->send();
                    $paymentMailer->sendInternally();
                }
            }

        }

        return $result;
    }

    public function mapToDatabaseDateField($stateId)
    {
        $state = $this->statusReader->getById($stateId);

        if (empty($state) || NULL == $state) {
            return array();
        }

        var_dump($state);

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
            default:
                return array('field' => 'cancelled');
        }
    }

}