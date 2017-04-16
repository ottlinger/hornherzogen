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

            // send out mails
            if ($this->formHelper->isSetAndNotEmptyInArray($mappingResult, 'mail')) {
                if ('PaymentMailer' == $mappingResult['mail']) {
                    $paymentMailer = new PaymentMailer($applicantId);
                    $paymentMailer->send();
                    $paymentMailer->sendInternally();
                }
            }

            // update database fields
            $result = is_numeric($this->updateInDatabase($applicantId, $stateId, $mappingResult));
        }

        return $result;
    }

    public function mapToDatabaseDateField($stateId)
    {
        $state = $this->statusReader->getById($stateId);

        if (empty($state) || NULL == $state || sizeof($state) < 1) {
            return array();
        }

        switch ($state[0]['name']) {
            case 'WAITING_FOR_PAYMENT':
                return array('mail' => 'PaymentMailer', 'field' => 'paymentmailed');

            case 'PAID':
                return array('field' => 'paymentreceived');

            case 'APPLIED':
                return array('field' => 'created');

            case 'REGISTERED':
            case 'CONFIRMED':
                return array('field' => 'booked');

            case 'SPAM':
            case 'REJECTED':
            case 'CANCELLED':
            default:
                return array('field' => 'cancelled');
        }
    }

    public function updateInDatabase($applicantId, $stateId, $mappingResult)
    {
        if ($this->isHealthy() && isset($stateId) && isset($applicantId) && is_numeric($applicantId) && is_numeric($stateId)) {

            $sql = "UPDATE applicants SET statusId=" . $this->databaseHelper->trimAndMask($stateId) . " " . $this->mapMappingToSQL($mappingResult) . " WHERE id=" . $this->databaseHelper->trimAndMask($applicantId);
            $stmt = $this->database->prepare($sql);

            // execute the query
            $result = $stmt->execute();
            $this->databaseHelper->logDatabaseErrors($result, $this->database);

            return $stmt->rowCount();
        }
        return NULL;
    }

    public function mapMappingToSQL($mappingResult)
    {
        if ($this->formHelper->isSetAndNotEmptyInArray($mappingResult, 'field')) {
            return " , " . $this->formHelper->filterUserInput($mappingResult['field']) . " = " . $this->databaseHelper->trimAndMask($this->formHelper->timestamp());
        }

        return '';
    }

}