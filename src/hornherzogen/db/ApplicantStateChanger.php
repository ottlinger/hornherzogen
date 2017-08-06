<?php

declare(strict_types=1);

namespace hornherzogen\db;

use hornherzogen\mail\PaymentMailer;

class ApplicantStateChanger extends BaseDatabaseWriter
{
    private $statusReader;

    public function __construct($databaseConnection = null)
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
        $result = false;

        if (!self::isHealthy() || null === $applicantId || null === $stateId) {
            return false;
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
            $dbResult = $this->updateInDatabase($applicantId, $stateId, $mappingResult);
            $result = is_numeric($dbResult);
        }

        return $result;
    }

    public function mapToDatabaseDateField($stateId)
    {
        $state = $this->statusReader->getById($stateId);

        if (empty($state) || null == $state || count($state) < 1) {
            return [];
        }

        switch ($state[0]['name']) {
            case 'WAITING_FOR_PAYMENT':
                return ['mail' => 'PaymentMailer', 'field' => 'paymentmailed'];

            // Issue #91: ConfirmationMailer sends mail as batch, thus no mail argument here
            case 'BOOKED':
                return ['field' => 'booked'];

            case 'PAID':
                return ['field' => 'paymentreceived'];

            case 'APPLIED':
                return ['field' => 'created'];

            case 'REGISTERED':
            case 'CONFIRMED':
                return ['field' => 'booked'];

            case 'SPAM':
            case 'REJECTED':
            case 'CANCELLED':
            default:
                return ['field' => 'cancelled'];
        }
    }

    public function updateInDatabase($applicantId, $stateId, $mappingResult)
    {
        if ($this->isHealthy() && isset($stateId) && isset($applicantId) && is_numeric($applicantId) && is_numeric($stateId)) {
            $sql = 'UPDATE applicants SET statusId='.$this->databaseHelper->trimAndMask($stateId).' '.$this->mapMappingToSQL($mappingResult).' WHERE id='.$this->databaseHelper->trimAndMask($applicantId);
            $stmt = $this->database->prepare($sql);

            // execute the query
            $result = $stmt->execute();
            $this->databaseHelper->logDatabaseErrors($result, $this->database);

            return $stmt->rowCount();
        }
    }

    public function mapMappingToSQL($mappingResult)
    {
        if ($this->formHelper->isSetAndNotEmptyInArray($mappingResult, 'field')) {
            return ' , '.$this->formHelper->filterUserInput($mappingResult['field']).' = '.$this->databaseHelper->trimAndMask(date('Y-m-d H:i:s'));
        }

        return '';
    }
}
