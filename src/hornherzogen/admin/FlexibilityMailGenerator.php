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
        // TODO extract to localization
        if ('de' != $this->applicant->getLanguage()) {
            $subject = "Application Herzogenhorn - week change possible?";
            $body = "Hi, english text goes here.";
        } else {
            $subject = "Anmeldung Herzogenhorn - Wochenwechsel möglich?";
            $body = "Hi, die von Dir gewählte Woche ist ausgebucht. Kannst Du Dir vorstellen in die andere Woche zu wechseln? Danke, das Orgateam aus Berlin";
        }
        return $subject;
    }

    public function getBody()
    {
        // TODO extract to localization
        if ('de' != $this->applicant->getLanguage()) {
            $subject = "Application Herzogenhorn - week change possible?";
            $body = "Hi, english text goes here.";
        } else {
            $subject = "Anmeldung Herzogenhorn - Wochenwechsel möglich?";
            $body = "Hi, die von Dir gewählte Woche ist ausgebucht. Kannst Du Dir vorstellen in die andere Woche zu wechseln? Danke, das Orgateam aus Berlin";
        }
        return $body;
    }
    
}
