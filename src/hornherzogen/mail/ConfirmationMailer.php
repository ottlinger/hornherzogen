<?php
declare(strict_types=1);

namespace hornherzogen\mail;

use hornherzogen\Applicant;
use hornherzogen\ConfigurationWrapper;
use hornherzogen\db\ApplicantDatabaseReader;
use hornherzogen\db\StatusDatabaseReader;
use hornherzogen\FormHelper;
use hornherzogen\HornLocalizer;
use hornherzogen\admin\BankingConfiguration;
use MessageFormatter;

/**
 * Class ConfirmationMailer sends a mail to a given list of applicants.
 * @package hornherzogen\mail
 */
class ConfirmationMailer
{
    const TEST_APPLICANT = -4711;

    // internal members
    public $uiPrefix = "<h3 style='color: rebeccapurple; font-weight: bold;'>";
    private $formHelper;
    private $applicants = NULL;
    private $localizer;
    private $config;
    private $accountConfiguration;
    private $headerGenerator;

    // defines how the success messages are being shown in the UI
    private $statusReader;

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

        date_default_timezone_set('Europe/Berlin');
    }

    public function send()
    {
        if (!$this->hasValidApplicant()) {
            return 'Nothing to send.';
        }

        $replyto = $this->config->registrationmail();
        $headers = $this->headerGenerator->getHeaders($replyto);

        $withParam = new MessageFormatter($this->applicants->getLanguage(), $GLOBALS['messages'][$this->applicants->getLanguage()]["CMAIL.SUBJECT"]);
        $subject = $withParam->format(array($this->formHelper->timestamp()));

        // we need the key directly for the language of the applicant!
        $encoded_subject = "=?UTF-8?B?" . base64_encode($subject) . "?=";

        if ($this->config->sendregistrationmails()) {
            mail($this->applicants->getEmail(), $encoded_subject, $this->getMailtext(), implode("\r\n", $headers), "-f " . $replyto);
            $appliedAt = $this->formHelper->timestamp();
            $this->applicants->setConfirmed($appliedAt);

            return $this->uiPrefix . $this->localizer->i18nParams('CMAIL.APPLICANT', $appliedAt) . "</h3>";
        }

        return '';
    }


    public function hasValidApplicant()
    {
        return boolval($this->applicants != null && !(empty($this->applicants)));
    }

    public function getMailtext()
    {
        // all non German customers will get an English mail
        if ($this->applicants->getLanguage() != 'de') {
            return $this->getEnglishMailtext();
        }

        $mailtext =
            '
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>finale Bestätigung Herzogenhorn Woche ' . $this->applicants->getWeek() . '</title >
        </head>
        <body>
            <h1>Herzogenhorn ' . $this->localizer->i18n('CONST.YEAR') . ' - Zahlungsaufforderung für Woche ' . $this->applicants->getWeek() . '</h1>
            <h2>
                Hallo ' . $this->applicants->getFirstname() . ',</h2>
                <p>wir haben die Lehrgangswoche ' . $this->applicants->getWeek() . ' soweit durchgeplant und bitten Dich innerhalb der nächsten 2 Wochen das Lehrgangsgeld als verbindliche Bestätigung Deiner Anmeldung zu überweisen.
                </p>
                <p>Bitte verwende die folgende Bankverbindung
                <ul>
                <li>Kontoinhaber: ' . $this->accountConfiguration->getAccountHolder() . '</li>
                <li>IBAN: ' . $this->accountConfiguration->getIban() . '</li>
                <li>BIC: ' . $this->accountConfiguration->getBic() . '</li>
                <li>Verwendungszweck: Herzogenhornseminar ' . $this->localizer->i18n('CONST.YEAR') . "/Woche " . $this->applicants->getWeek() . "/" . $this->applicants->getFirstname() . ' ' . $this->applicants->getLastname() . '/#' . $this->applicants->getPersistenceId() . '</li>
                </ul>
                </p>
                <h3>
                Sonnige Grüße aus Berlin<br />
                von Benjamin und Philipp</h3>
            </h2>
        </body>
    </html>';

        return $mailtext;
    }

    public function getEnglishMailtext()
    {
        $mailtext =
            '
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Final confirmation for payment for Herzogenhorn seminar week ' . $this->applicants->getWeek() . '</title >
        </head>
        <body>
            <h1>Herzogenhorn ' . $this->localizer->i18n('CONST.YEAR') . ' - request for payment seminar week ' . $this->applicants->getWeek() . '</h1>
            <h2>
                Hi ' . $this->applicants->getFirstname() . ',</h2>
                <p>thanks for your patience. We\'ve finished planning the seminar\'s week ' . $this->applicants->getWeek() . ' 
                and would like to request your payment in the next 14 days in order to finally fulfil your seminar application.</p>
                <p>Please use the following bank account and notes to transfer the money properly:
                <ul>
                <li>Account holder: ' . $this->accountConfiguration->getAccountHolder() . '</li>
                <li>IBAN: ' . $this->accountConfiguration->getIban() . '</li>
                <li>BIC: ' . $this->accountConfiguration->getBic() . '</li>
                <li>Reason for payment: Herzogenhorn seminar ' . $this->localizer->i18n('CONST.YEAR') . "/Week " . $this->applicants->getWeek() . "/" . $this->applicants->getFirstname() . ' ' . $this->applicants->getLastname() . '/#' . $this->applicants->getPersistenceId() . '</li>
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

    /**
     * Send mails to us after sending a mail to the person that registered.
     */
    public function sendInternally()
    {
        if (!$this->hasValidApplicant()) {
            return 'Nothing to send internally.';
        }

        if ($this->config->sendinternalregistrationmails()) {

            $replyto = $this->config->registrationmail();

            $encoded_subject = "=?UTF-8?B?" . base64_encode("finale Bestätigung ausgesendet - Woche " . $this->applicants->getWeek()) . "?=";
            $headers = $this->headerGenerator->getHeaders($replyto);

            mail($replyto, $encoded_subject, $this->getInternalMailtext(), implode("\r\n", $headers), "-f " . $replyto);

            return $this->uiPrefix . $this->localizer->i18nParams('CMAIL.INTERNAL', $this->formHelper->timestamp()) . "</h3>";
        }
        return '';
    }

    public function getInternalMailtext()
    {
        $mailtext =
            '
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Lehrgangsbestätigung versendet für Woche ' . $this->applicants->getWeek() . '</title >
        </head>
        <body>
            <h1>Herzogenhorn ' . $this->localizer->i18n('CONST.YEAR') . ' - Zahlungsbestätigung für Woche ' . $this->applicants->getWeek() . ' verschickt</h1>
            <h2>Anmeldungsdetails</h2>
                <p>es ging gegen ' . $this->formHelper->timestamp() . ' die Zahlungsbestätigung per E-Mail raus:</p>
                <ul>
                <li>Woche: ' . $this->applicants->getWeek() . '</li>
                <li>Anrede: ' . ($this->applicants->getGender() === 'male' ? 'Herr' : 'Frau') . '</li>
                <li>interner Name: ' . $this->applicants->getFullname() . '</li>
                <li>Umbuchbar? ' . ($this->applicants->getFlexible() == 1 ? 'ja' : 'nein') . '</li>
                <li>E-Mail: ' . $this->applicants->getEmail() . '</li>
                <li>Land: ' . $this->applicants->getCountry() . '</li>
                <li>Dojo:  ' . $this->applicants->getDojo() . '</li>
                <li>TWA: ' . $this->applicants->getTwaNumber() . '</li>
                <li>Betrag: ' . $this->getSeminarPrice() . '</li>
                </ul>
            </h2>
             DONE - juchhe!
            </p>
        </body>
    </html>';

        return $mailtext;
    }

}
