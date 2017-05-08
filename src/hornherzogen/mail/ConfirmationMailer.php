<?php
declare(strict_types=1);

namespace hornherzogen\mail;

use hornherzogen\admin\BankingConfiguration;
use hornherzogen\Applicant;
use hornherzogen\ConfigurationWrapper;
use hornherzogen\db\ApplicantDatabaseReader;
use hornherzogen\db\ApplicantStateChanger;
use hornherzogen\db\StatusDatabaseReader;
use hornherzogen\FormHelper;
use hornherzogen\HornLocalizer;
use MessageFormatter;

/**
 * Class ConfirmationMailer sends a mail to a given list of applicants.
 * @package hornherzogen\mail
 */
class ConfirmationMailer
{
    const TEST_APPLICANT = -4711;

    // internal members
    private $formHelper;
    private $applicants = NULL;
    private $localizer;
    private $config;
    private $accountConfiguration;
    private $headerGenerator;
    private $stateChanger;

    // defines how the success messages are being shown in the UI
    private $statusReader;

    function __construct($applicants = NULL)
    {
        $this->reader = new ApplicantDatabaseReader();

        if (!isset($applicants) && !empty($applicants)) {
            $this->applicants = self::createTestApplicants();
        } else {
            $this->applicants = $applicants;
        }

        $this->headerGenerator = new MailHeaderGenerator();
        $this->formHelper = new FormHelper();

        $this->localizer = new HornLocalizer();
        $this->config = new ConfigurationWrapper();
        $this->statusReader = new StatusDatabaseReader();
        $this->accountConfiguration = new BankingConfiguration();
        $this->stateChanger = new ApplicantStateChanger();

        date_default_timezone_set('Europe/Berlin');
    }

    public static function createTestApplicants()
    {
        $testApplicant = new Applicant();
        $testApplicant->setFirstname("Emil");
        $testApplicant->setLastname("Mustermann");
        $testApplicant->setTwaNumber("CC-0815");
        $testApplicant->setPersistenceId(4711);
        $testApplicant->setWeek(2);
        $testApplicant->setLanguage('de');
        $testApplicant->setCurrentStatus(6); // PAID
        $testApplicant->setEmail("shouldnotwork"); // do not really send an email
        return array($testApplicant);
    }

    public function sendAsBatch()
    {
        $counter = 0;
        $bookedDBId = $this->statusReader->getByName("BOOKED")[0]['id'];

        foreach ($this->applicants as $applicant) {
            echo "<h2>Sending out to applicant #" . $counter++ . " / " . $applicant->getFullName() . "</h2>";

            // get a fresh timestamp
            $this->formHelper = new FormHelper();

            $mailResult = $this->send($applicant);

            echo $mailResult;
            echo $this->sendInternally($applicant);

            // TODO change state if sending was successful, parse from above $mailResult
            echo "Changing state in database to 'BOOKED' resulted in " . boolval($this->stateChanger->changeStateTo($applicant->getPersistenceId(), $bookedDBId));
            echo "<hr/>";
        }
    }

    function send($applicant)
    {
        if (!isset($applicant)) {
            return 'Nothing to send.';
        }

        $replyto = $this->config->registrationmail();
        $headers = $this->headerGenerator->getHeaders($replyto);

        $withParam = new MessageFormatter($applicant->getLanguage(), $GLOBALS['messages']['' . $applicant->getLanguage()]["CMAIL.SUBJECT"]);
        $subject = $withParam->format(array($this->formHelper->timestamp()));

        // we need the key directly for the language of the applicant!
        $encoded_subject = "=?UTF-8?B?" . base64_encode($subject) . "?=";

        if ($this->config->sendregistrationmails()) {
            $mailResult = mail($applicant->getEmail(), $encoded_subject, $this->getMailtext($applicant), implode("\r\n", $headers), "-f " . $replyto);
            $appliedAt = $this->formHelper->timestamp();

            if($mailResult) {
                $applicant->setBookedAt($appliedAt);
            }
            
            return $this->getColouredUIPrefix($mailResult) . $this->localizer->i18nParams('CMAIL.APPLICANT', $appliedAt . " returnCode:" . $mailResult) . "</h3>";
        }

        return '';
    }

    public function getMailtext($applicant)
    {
        // all non German customers will get an English mail
        if ($applicant->getLanguage() != 'de') {
            return $this->getEnglishMailtext();
        }

        $mailtext =
            '
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>finale Bestätigung Herzogenhorn Woche ' . $applicant->getWeek() . '</title >
        </head>
        <body>
            <h1>Herzogenhorn ' . $this->localizer->i18n('CONST.YEAR') . ' - finale Anmeldebestätigung für Woche ' . $applicant->getWeek() . '</h1>
            <h2>
                Hallo ' . $applicant->getFirstname() . ',</h2>
                <p>danke für den Eingang Deiner Zahlung für die Lehrgangswoche ' . $applicant->getWeek() . '.
                </p>
                <p>Du bist mit dieser Mail final für das Herzogenhorn angemeldet. Aktuell bist Du für den Raum
                <ul>
                <li>TBD ' . $this->accountConfiguration->getAccountHolder() . '</li>
                </ul>
                eingeplant.
                </p>
                <p>
                Bitte beachte die folgenden <b>Stornoregeln</b> und kontaktiere uns im Notfall, sodass Aikidoka auf der Warteliste nachrücken können:
                <ul>
                <li>bis 4 Wochen vor Lehrgangsbeginn kostenfrei</li>
                <li>bis 2 Wochen vor Lehrgangsbeginn 50% der Kosten</li>
                <li>weniger als 2 Wochen vor Lehrgangsbeginn 100% der Kosten</li>
                </ul>
                </p>
                <h3>
                Sonnige Grüße aus Berlin, wir freuen uns auf das gemeinsame Training!<br />
                von Benjamin und Philipp</h3>
            </h2>
        </body>
    </html>';

        return $mailtext;
    }

    public function getEnglishMailtext($applicant)
    {
        $mailtext =
            '
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Final confirmation for payment for Herzogenhorn seminar week ' . $applicant->getWeek() . '</title >
        </head>
        <body>
            <h1>Herzogenhorn ' . $this->localizer->i18n('CONST.YEAR') . ' - request for payment seminar week ' . $applicant->getWeek() . '</h1>
            <h2>
                Hi ' . $applicant->getFirstname() . ',</h2>
                <p>thanks for your patience. We\'ve finished planning the seminar\'s week ' . $applicant->getWeek() . ' 
                and would like to request your payment in the next 14 days in order to finally fulfil your seminar application.</p>
                <p>Please use the following bank account and notes to transfer the money properly:
                <ul>
                <li>Account holder: ' . $this->accountConfiguration->getAccountHolder() . '</li>
                <li>IBAN: ' . $this->accountConfiguration->getIban() . '</li>
                <li>BIC: ' . $this->accountConfiguration->getBic() . '</li>
                <li>Reason for payment: Herzogenhorn seminar ' . $this->localizer->i18n('CONST.YEAR') . "/Week " . $applicant->getWeek() . "/" . $applicant->getFirstname() . ' ' . $applicant->getLastname() . '/#' . $applicant->getPersistenceId() . '</li>
                <li>Amount: ' . $this->getSeminarPrice() . '</li>
                </ul>
                </p>
                <p>
                If we do not receive your payment in time we are forced to cancel your reservation -<br />
                </p>
                <h3>
                All the best from Berlin<br />
                Benjamin und Philipp</h3>
            </h2>
        </body>
    </html>';

        return $mailtext;
    }

    public function getColouredUIPrefix($mailResult)
    {
        if (boolval($mailResult)) {
            return "<h3 style='color: darkgreen; font-weight: bold;'>";
        }
        return "<h3 style='color: red; font-weight: bold;'>";
    }

    function sendInternally($applicant = NULL)
    {
        if (!isset($applicant)) {
            return 'Nothing to send internally.';
        }

        if ($this->config->sendinternalregistrationmails()) {

            $replyto = $this->config->registrationmail();

            $encoded_subject = "=?UTF-8?B?" . base64_encode("finale Bestätigung ausgesendet - Woche " . $applicant->getWeek()) . "?=";
            $headers = $this->headerGenerator->getHeaders($replyto);

            $mailResult = mail($replyto, $encoded_subject, $this->getInternalMailtext($applicant), implode("\r\n", $headers), "-f " . $replyto);

            return $this->getColouredUIPrefix($mailResult) . $this->localizer->i18nParams('CMAIL.INTERNAL', $this->formHelper->timestamp() . " returnCode: " . $mailResult) . "</h3>";
        }
        return '';
    }

    public function getInternalMailtext($applicant)
    {
        $mailtext =
            '
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Lehrgangsbestätigung versendet für Woche ' . $applicant->getWeek() . '</title >
        </head>
        <body>
            <h1>Herzogenhorn ' . $this->localizer->i18n('CONST.YEAR') . ' - Zahlungsbestätigung für Woche ' . $applicant->getWeek() . ' verschickt</h1>
            <h2>Anmeldungsdetails</h2>
                <p>es ging gegen ' . $this->formHelper->timestamp() . ' die Zahlungsbestätigung per E-Mail raus:</p>
                <ul>
                <li>Woche: ' . $applicant->getWeek() . '</li>
                <li>Anrede: ' . ($applicant->getGender() === 'male' ? 'Herr' : 'Frau') . '</li>
                <li>interner Name: ' . $applicant->getFullname() . '</li>
                <li>Umbuchbar? ' . ($applicant->getFlexible() == 1 ? 'ja' : 'nein') . '</li>
                <li>E-Mail: ' . $applicant->getEmail() . '</li>
                <li>Land: ' . $applicant->getCountry() . '</li>
                <li>Dojo:  ' . $applicant->getDojo() . '</li>
                <li>TWA: ' . $applicant->getTwaNumber() . '</li>
                </ul>
            </h2>
             DONE - juchhe!
            </p>
        </body>
    </html>';

        return $mailtext;
    }

}
