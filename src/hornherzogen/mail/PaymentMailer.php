<?php
declare(strict_types=1);

namespace hornherzogen\mail;

use hornherzogen\ConfigurationWrapper;
use hornherzogen\db\ApplicantDatabaseReader;
use hornherzogen\db\ApplicantDatabaseWriter;
use hornherzogen\db\StatusDatabaseReader;
use hornherzogen\FormHelper;
use hornherzogen\HornLocalizer;

class PaymentMailer
{
    // TODO add i18n keys PMAIL .... stuff

    // internal members
    public $uiPrefix = "<h3 style='color: rebeccapurple; font-weight: bold;'>";
    private $formHelper;
    private $applicationInput;
    private $reader;
    private $localizer;
    private $config;
    private $dbWriter;
    private $headerGenerator;

    // defines how the success messages are being shown in the UI
    private $statusReader;

    function __construct($applicantId)
    {
        $this->reader = new ApplicantDatabaseReader();
        $this->applicationInput = $this->reader->getById($applicantId);

        $this->headerGenerator = new MailHeaderGenerator();
        $this->formHelper = new FormHelper();

        $this->localizer = new HornLocalizer();
        $this->config = new ConfigurationWrapper();
        $this->dbWriter = new ApplicantDatabaseWriter();
        $this->statusReader = new StatusDatabaseReader();

        date_default_timezone_set('Europe/Berlin');
    }

    public function send()
    {
        $replyto = $this->config->registrationmail();
        $headers = $this->headerGenerator->getHeaders($replyto);

        $encoded_subject = "=?UTF-8?B?" . base64_encode($this->localizer->i18nParams('PMAIL.SUBJECT', $this->formHelper->timestamp())) . "?=";

        if ($this->config->sendregistrationmails() && !$this->isMailSent()) {
            mail($this->applicationInput->getEmail(), $encoded_subject, $this->getMailtext(), implode("\r\n", $headers), "-f " . $replyto);
            $appliedAt = $this->formHelper->timestamp();
            $this->applicationInput->setPaymentRequestedAt($appliedAt);

            $this->setStatusAppliedIfPossible();

            return $this->uiPrefix . $this->localizer->i18nParams('PMAIL.APPLICANT', $appliedAt) . "</h3>";
        }
        $this->applicationInput->setMailSent(true);

        return '';
    }

    public function isMailSent()
    {
        return boolval($this->applicationInput->isMailSent());
    }

    public function getMailtext()
    {
        // all non German customers will get an English confirmation mail
        if ($this->localizer->getLanguage() != 'de') {
            return $this->getEnglishMailtext();
        }

        $mailtext =
            '
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Zahlungsaufforderung Herzogenhorn Woche ' . $this->applicationInput->getWeek() . '</title >
        </head>
        <body>
            <h1>Herzogenhorn ' . $this->localizer->i18n('CONST.YEAR') . ' - Zahlungsaufforderung für Woche ' . $this->applicationInput->getWeek() . '</h1>
            <h2>
                Hallo ' . $this->applicationInput->getFirstname() . ',</h2>
                <p>wir haben die Lehrgangswoche soweit geplant und bitten Dich nun um ' . $this->formHelper->timestamp() . '
                innerhalb der nächsten 2 Wochen zu überweisen.
                </p>
                <p>Bitte verwende die folgende Bankverbindung
                <ul>
                <li>Anrede: ' . ($this->applicationInput->getGender() === 'male' ? 'Herr' : 'Frau') . '</li>
                <li>Verwendungszweck: ' . $this->applicationInput->getFirstname() . ' ' . $this->applicationInput->getLastname() . '</li>
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

    private function getEnglishMailtext()
    {
        $mailtext =
            '
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Request for payment for Herzogenhorn seminar week ' . $this->applicationInput->getWeek() . '</title >
        </head>
        <body>
            <h1>Herzogenhorn ' . $this->localizer->i18n('CONST.YEAR') . ' - request for payment seminar week ' . $this->applicationInput->getWeek() . '</h1>
            <h2>
                Hi ' . $this->applicationInput->getFirstname() . ',</h2>
                <p>thanks for your patience. We\'ve planned the seminar week ' . $this->applicationInput->getWeek() . ' at  ' . $this->formHelper->timestamp() . '. 
                and would like to request your payment in the next 14 days in order to fulfill your seminar application.</p>
                <p>Please transfer the money to the following bank account:
                <ul>
                <li>Gender: ' . ($this->applicationInput->getGender() === 'male' ? 'Mr.' : 'Mrs.') . '</li>
                <li>Reason for payment: ' . $this->applicationInput->getFirstname() . ' ' . $this->applicationInput->getLastname() . '</li>
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
        if (strlen($this->applicationInput->getTwaNumber())) {
            return "250,00 €";
        }
        return "300,00 €";
    }

    private function setStatusAppliedIfPossible()
    {
        $statusApplied = $this->statusReader->getByName('WAITING_FOR_PAYMENT');
        if (isset($statusApplied)) {
            $this->applicationInput->setCurrentStatus($statusApplied[0]['id']);
        }
    }

    /**
     * Send mails to us after sending a mail to the person that registered.
     */
    public function sendInternally()
    {
        if ($this->config->sendinternalregistrationmails() && !$this->isMailSent()) {

            $replyto = $this->config->registrationmail();

            $encoded_subject = "=?UTF-8?B?" . base64_encode("Bezahlung Herzogenhorn angefordert - Woche " . $this->applicationInput->getWeek()) . "?=";
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
            <title>Zahlungsbestätigung versendet für Woche ' . $this->applicationInput->getWeek() . ' </title >
        </head>
        <body>
            <h1>Herzogenhorn ' . $this->localizer->i18n('CONST.YEAR') . ' - Zahlungsbestätigung für Woche ' . $this->applicationInput->getWeek() . 'verschickt</h1>
            <h2>Anmeldungsdetails</h2>
                <p>es ging gegen ' . $this->formHelper->timestamp() . ' die Zahlungsbestätigung raus:</p>
                <ul>
                <li>Woche: ' . $this->applicationInput->getWeek() . '</li>
                <li>Anrede: ' . ($this->applicationInput->getGender() === 'male' ? 'Herr' : 'Frau') . '</li>
                <li>interner Name: ' . $this->applicationInput->getFullname() . '</li>
                <li>Umbuchbar? ' . ($this->applicationInput->getFlexible() == 1 ? 'ja' : 'nein') . '</li>
                <li>Land: ' . $this->applicationInput->getCountry() . '</li>
                <li>Dojo:  ' . $this->applicationInput->getDojo() . '</li>
                <li>TWA: ' . $this->applicationInput->getTwaNumber() . '</li>
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
