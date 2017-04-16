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

class PaymentMailer
{
    const TEST_APPLICANT_ID = -4711;

    // internal members
    public $uiPrefix = "<h3 style='color: rebeccapurple; font-weight: bold;'>";
    private $formHelper;
    private $applicant = NULL;
    private $reader;
    private $localizer;
    private $config;
    private $accountConfiguration;
    private $headerGenerator;

    // defines how the success messages are being shown in the UI
    private $statusReader;

    public static function createTestApplicant()
    {
        $a = new Applicant();
        $a->setFirstname("Emil");
        $a->setLastname("Mustermann");
        $a->setTwaNumber("CC-0815");
        $a->setPersistenceId(4711);
        $a->setWeek(2);
        return $a;
    }

    function __construct($applicantId)
    {
        $this->reader = new ApplicantDatabaseReader();

        if (self::TEST_APPLICANT_ID == $applicantId) {
            $this->applicant = self::createTestApplicant();
        } else {
            $findApplicant = $this->reader->getById($applicantId);
            if (!empty($findApplicant)) {
                $this->applicant = $this->reader->getById($applicantId)[0];
            }
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
            return 'Nothing to send';
        }

        $replyto = $this->config->registrationmail();
        $headers = $this->headerGenerator->getHeaders($replyto);

        $encoded_subject = "=?UTF-8?B?" . base64_encode($this->localizer->i18nParams('PMAIL.SUBJECT', $this->formHelper->timestamp())) . "?=";

        if ($this->config->sendregistrationmails()) {
            mail($this->applicant->getEmail(), $encoded_subject, $this->getMailtext(), implode("\r\n", $headers), "-f " . $replyto);
            $appliedAt = $this->formHelper->timestamp();
            $this->applicant->setPaymentRequestedAt($appliedAt);

            return $this->uiPrefix . $this->localizer->i18nParams('PMAIL.APPLICANT', $appliedAt) . "</h3>";
        }

        return '';
    }


    public function hasValidApplicant()
    {
        return boolval($this->applicant != null && !(empty($this->applicant)));
    }

    public function getMailtext()
    {
        // all non German customers will get an English mail
        if ($this->localizer->getLanguage() != 'de') {
            return $this->getEnglishMailtext();
        }

        $mailtext =
            '
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Zahlungsaufforderung Herzogenhorn Woche ' . $this->applicant->getWeek() . '</title >
        </head>
        <body>
            <h1>Herzogenhorn ' . $this->localizer->i18n('CONST.YEAR') . ' - Zahlungsaufforderung für Woche ' . $this->applicant->getWeek() . '</h1>
            <h2>
                Hallo ' . $this->applicant->getFirstname() . ',</h2>
                <p>wir haben die Lehrgangswoche soweit durchgeplant und bitten Dich nun um ' . $this->formHelper->timestamp() . '
                innerhalb der nächsten 2 Wochen das Lehrgangsgeld als verbindliche Bestätigung Deiner Anmeldung zu überweisen.
                </p>
                <p>Bitte verwende die folgende Bankverbindung
                <ul>
                <li>Kontoinhaber: ' . $this->accountConfiguration->getAccountHolder() . '</li>
                <li>IBAN: ' . $this->accountConfiguration->getIban() . '</li>
                <li>BIC: ' . $this->accountConfiguration->getBic() . '</li>
                <li>Verwendungszweck: Herzogenhornseminar ' . $this->localizer->i18n('CONST.YEAR') . "/Woche " . $this->applicant->getWeek() . "/" . $this->applicant->getFirstname() . ' ' . $this->applicant->getLastname() . '/#' . $this->applicant->getPersistenceId() . '</li>
                <li>Betrag: ' . $this->getSeminarPrice() . '</li>
                </ul>
                </p>
                <p>
                Danke für Deine Geduld und wir freuen uns Dich zu sehen - <br />
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
            <title>Request for payment for Herzogenhorn seminar week ' . $this->applicant->getWeek() . '</title >
        </head>
        <body>
            <h1>Herzogenhorn ' . $this->localizer->i18n('CONST.YEAR') . ' - request for payment seminar week ' . $this->applicant->getWeek() . '</h1>
            <h2>
                Hi ' . $this->applicant->getFirstname() . ',</h2>
                <p>thanks for your patience. We\'ve planned the seminar week ' . $this->applicant->getWeek() . ' at ' . $this->formHelper->timestamp() . '. 
                and would like to request your payment in the next 14 days in order to fulfill your seminar application.</p>
                <p>Please transfer the money to the following bank account:
                <ul>
                <li>Gender: ' . ($this->applicant->getGender() === 'male' ? 'Mr.' : 'Mrs.') . '</li>
                <li>Reason for payment: ' . $this->applicant->getFirstname() . ' ' . $this->applicant->getLastname() . '</li>
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

    public function getSeminarPrice()
    {
        if ($this->hasValidApplicant() && NULL != $this->applicant->getTwaNumber() && strlen($this->applicant->getTwaNumber())) {
            return "250,00 €";
        }
        return "300,00 €";
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

            $encoded_subject = "=?UTF-8?B?" . base64_encode("Bezahlung Herzogenhorn angefordert - Woche " . $this->applicant->getWeek()) . "?=";
            $headers = $this->headerGenerator->getHeaders($replyto);

            mail($replyto, $encoded_subject, $this->getInternalMailtext(), implode("\r\n", $headers), "-f " . $replyto);

            return $this->uiPrefix . $this->localizer->i18nParams('PMAIL.INTERNAL', $this->formHelper->timestamp()) . "</h3>";
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
            <title>Zahlungsbestätigung versendet für Woche ' . $this->applicant->getWeek() . ' </title >
        </head>
        <body>
            <h1>Herzogenhorn ' . $this->localizer->i18n('CONST.YEAR') . ' - Zahlungsbestätigung für Woche ' . $this->applicant->getWeek() . 'verschickt</h1>
            <h2>Anmeldungsdetails</h2>
                <p>es ging gegen ' . $this->formHelper->timestamp() . ' die Zahlungsbestätigung raus:</p>
                <ul>
                <li>Woche: ' . $this->applicant->getWeek() . '</li>
                <li>Anrede: ' . ($this->applicant->getGender() === 'male' ? 'Herr' : 'Frau') . '</li>
                <li>interner Name: ' . $this->applicant->getFullname() . '</li>
                <li>Umbuchbar? ' . ($this->applicant->getFlexible() == 1 ? 'ja' : 'nein') . '</li>
                <li>Land: ' . $this->applicant->getCountry() . '</li>
                <li>Dojo:  ' . $this->applicant->getDojo() . '</li>
                <li>TWA: ' . $this->applicant->getTwaNumber() . '</li>
                <li>Betrag: ' . $this->getSeminarPrice() . '</li>
                </ul>
            </h2>
             Zahlungsfrist sind 2 Wochen!
            </p>
        </body>
    </html>';

        return $mailtext;
    }

}
