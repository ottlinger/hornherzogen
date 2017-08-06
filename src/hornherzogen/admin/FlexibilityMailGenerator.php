<?php

declare(strict_types=1);

namespace hornherzogen\admin;

use hornherzogen\HornLocalizer;

class FlexibilityMailGenerator
{
    private $applicant;
    private $localizer;

    public function __construct($applicant)
    {
        $this->applicant = $applicant;
        $this->localizer = new HornLocalizer();
    }

    public function getSubject()
    {
        return $this->localizer->i18n('ADMIN.FLEX.SUBJECT');
    }

    public function getBody()
    {
        return $this->localizer->i18nParams('ADMIN.FLEX.BODY', [$this->applicant->getFirstname(), $this->applicant->getWeek(), $this->getOtherWeek()]);
    }

    public function getOtherWeek()
    {
        if ($this->applicant->getWeek() == 1) {
            return 2;
        }

        return 1;
    }
}
