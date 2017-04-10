<?php
declare(strict_types=1);

namespace hornherzogen\admin;

use hornherzogen\HornLocalizer;

class FlexibilityMailGenerator
{
    private $applicant;
    private $localizer;

    function __construct($applicant)
    {
        $this->applicant = $applicant;
        $this->localizer = new HornLocalizer();
    }

    public function getSubject()
    {
        return $this->localizer->i18n('ADMIN.FLEX.BODY');
    }

    public function getBody()
    {
        return $this->localizer->i18nParams('ADMIN.FLEX.SUBJECT', array($this->applicant->getFirstname(), $this->applicant->getWeek(), $this->getOtherWeek()));
    }

    public function getOtherWeek()
    {
        if ($this->applicant->getWeek() == 1) {
            return 2;
        }
        return 1;
    }

}
