<?php
namespace hornherzogen;


class ConfigurationWrapper
{

    public static function mail() {
        if($GLOBALS['horncfg']) {
            return $GLOBALS['horncfg']['mail'];
        }
    }

/*
| mail | complete email address, may contain a subject line as well |
| pdf | complete link to the seminar announcement (PDF) |
| registrationmail | email address that all registrations are send to |
| sendregistrationmails | boolean, whether to send registration mails to customers |
| sendinternalregistrationmails | boolean, whether to send mails internally upon registration via web form |
*/


}