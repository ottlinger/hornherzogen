<?php
declare(strict_types=1);

namespace hornherzogen\admin;

use hornherzogen\ConfigurationWrapper;

class BankingConfiguration extends ConfigurationWrapper
{
    private $ibanData;
    private $bicData;
    private $accountHolder;

    public function __construct()
    {
        parent::__construct();
        $this->ibanData = self::getFromHornConfiguration('iban');
        $this->bicData = self::getFromHornConfiguration('bic');
        $this->accountHolder = self::getFromHornConfiguration('accountholder');
    }

    public function __toString()
    {
        $status = "<pre>Payment configuration is: " . self::LINEBREAK;
        $status .= "Account holder: " . self::maskWithAsterisk($this->formHelper->filterUserInput($this->getAccountHolder()), 10) . self::LINEBREAK;
        $status .= "IBAN: " . self::maskWithAsterisk($this->formHelper->filterUserInput($this->getIban()), 7) . self::LINEBREAK;
        $status .= "BIC: " . self::maskWithAsterisk($this->formHelper->filterUserInput($this->getBic()), 7) . self::LINEBREAK;
        $status .= "</pre>";
        return $status;
    }


    public function getIban()
    {
        return $this->ibanData;
    }

    public function getBic()
    {
        return $this->bicData;
    }

    public function getAccountHolder()
    {
        return $this->accountHolder;
    }

}
