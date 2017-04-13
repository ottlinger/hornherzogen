<?php
declare(strict_types=1);

namespace hornherzogen\mail;

use hornherzogen\db\ApplicantDatabaseWriter;
use hornherzogen\db\StatusDatabaseReader;
use hornherzogen\FormHelper;
use hornherzogen\GitRevision;
use hornherzogen\HornLocalizer;
use hornherzogen\ConfigurationWrapper;

class PaymentMailer
{
    // TODO add i18n keys PMAIL .... stuff

    // internal members
    public $uiPrefix = "<h3 style='color: rebeccapurple; font-weight: bold;'>";
    private $formHelper;
    private $applicationInput;
    private $revision;
    private $localizer;
    private $config;
    private $dbWriter;

    // defines how the success messages are being shown in the UI
    private $statusReader;

    function __construct($applicantId)
    {
        $this->applicationInput = $applicantId;

        $this->formHelper = new FormHelper();
        $this->revision = new GitRevision();
        $this->localizer = new HornLocalizer();
        $this->config = new ConfigurationWrapper();
        $this->dbWriter = new ApplicantDatabaseWriter();
        $this->statusReader = new StatusDatabaseReader();

        date_default_timezone_set('Europe/Berlin');
    }

    // In case you need authentication you should switch the the PEAR module
    // https://www.lifewire.com/send-email-from-php-script-using-smtp-authentication-and-ssl-1171197
    public function send()
    {
        $replyto = $this->config->registrationmail();

        $importance = 1; //1 UrgentMessage, 3 Normal

        $encoded_subject = "=?UTF-8?B?" . base64_encode($this->localizer->i18nParams('PMAIL.SUBJECT', $this->formHelper->timestamp())) . "?=";

        // set all necessary headers to prevent being treated as SPAM in some mailers, headers must not start with a space
        $headers = array();
        $headers[] = 'MIME-Version: 1.0';

        $headers[] = 'X-Priority: ' . $importance;
        $headers[] = 'Importance: ' . $importance;
        $headers[] = 'X-MSMail-Priority: High';

        $headers[] = 'Reply-To: ' . $replyto;
        // https://api.drupal.org/api/drupal/includes%21mail.inc/function/drupal_mail/6.x
        $headers[] = 'From: ' . $replyto;
        $headers[] = 'Return-Path: ' . $replyto;
        $headers[] = 'Errors-To: ' . $replyto;

        $headers[] = 'Content-type: text/html; charset=UTF-8';
        $headers[] = 'Date: ' . date("r");
        $headers[] = 'Message-ID: <' . md5(uniqid(microtime())) . '@' . $_SERVER["SERVER_NAME"] . ">";
        $headers[] = 'X-Git-Revision: <' . $this->revision->gitrevision() . ">";
        $headers[] = 'X-Sender-IP: ' . $_SERVER["REMOTE_ADDR"];
        $headers[] = 'X-Mailer: PHP/' . phpversion();

        if ($this->config->sendregistrationmails() && !$this->isMailSent()) {
            mail($this->applicationInput->getEmail(), $encoded_subject, $this->getMailtext(), implode("\r\n", $headers), "-f " . $replyto);
            $appliedAt = $this->formHelper->timestamp();
            $this->applicationInput->setPaymentRequestedAt($appliedAt);

            $this->applicationInput->setLanguage($this->formHelper->extractMetadataForFormSubmission()['LANG']);
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

        $remarks = self::reformat($this->applicationInput->getRemarks());

        $mailtext =
            '
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Anmeldebestätigung Herzogenhorn Woche ' . $this->applicationInput->getWeek() . ' eingegangen</title >
        </head>
        <body>
            <h1>Herzogenhorn ' . $this->localizer->i18n('CONST.YEAR') . ' - Anmeldung für Woche ' . $this->applicationInput->getWeek() . '</h1>
            <h2>
                Hallo ' . $this->applicationInput->getFirstname() . ',</h2>
                <p>wir haben Deine Anmeldedaten für den Herzogenhornlehrgang 2017 um ' . $this->formHelper->timestamp() . '
                erhalten und melden uns sobald die Anmeldefrist abgelaufen ist und wir die beiden Wochen geplant haben.
                </p>
                <p>Deine Anmeldung erfolgte mit den folgenden Eingaben:
                <ul>
                <li>Anrede: ' . ($this->applicationInput->getGender() === 'male' ? 'Herr' : 'Frau') . '</li>
                <li>Name: ' . $this->applicationInput->getFirstname() . ' ' . $this->applicationInput->getLastname() . '</li>
                <li>Umbuchbar? ' . ($this->applicationInput->getFlexible() == 1 ? 'ja' : 'nein') . '</li>
                <li>Adresse: ' . $this->applicationInput->getStreet() . ' ' . $this->applicationInput->getHouseNumber() . '</li>
                <li>Stadt: ' . $this->applicationInput->getCity() . '</li>
                <li>Land: ' . $this->applicationInput->getCountry() . '</li>
                <li>Dojo:  ' . $this->applicationInput->getDojo() . '</li>
                <li>TWA: ' . $this->applicationInput->getTwaNumber() . '</li>
                <li>Graduierung: ' . $this->applicationInput->getGrading() . ' (seit ' . $this->applicationInput->getDateOfLastGrading() . ')</li>
                <li>Zimmer: ' . $this->applicationInput->getRoom() . '</li>
                <li>Person1: ' . $this->applicationInput->getPartnerOne() . '</li>
                <li>Person2: ' . $this->applicationInput->getPartnerTwo() . '</li>
                <li>Essenswunsch: ' . $this->applicationInput->getFoodCategory() . '</li>
                <li>Anmerkungen: ' . $remarks . '</li>
                <li>Woche: ' . $this->applicationInput->getWeek() . '</li>
                </ul>
                </p>
                <p>
                Danke für Deine Geduld und wir freuen uns Dich zu sehen - <br />
                </p>
                <h3>
                Bis dahin sonnige Grüße aus Berlin<br />
                von Benjamin und Philipp</h3>
            </h2>
        </body>
    </html>';

        return $mailtext;
    }

    private function getEnglishMailtext()
    {
        $remarks = self::reformat($this->applicationInput->getRemarks());

        $mailtext =
            '
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>You\'ve successfully applied for Herzogenhorn week ' . $this->applicationInput->getWeek() . '</title >
        </head>
        <body>
            <h1>Herzogenhorn ' . $this->localizer->i18n('CONST.YEAR') . ' - application for week ' . $this->applicationInput->getWeek() . '</h1>
            <h2>
                Hi ' . $this->applicationInput->getFirstname() . ',</h2>
                <p>we have received your application for Herzogenhorn week ' . $this->applicationInput->getWeek() . ' at ' . $this->formHelper->timestamp() . '. 
                After the end of submission we\'ll get back to you.
                </p>
                <p>Your application contained the following data that were saved in our database:
                <ul>
                <li>Gender: ' . ($this->applicationInput->getGender() === 'male' ? 'Mr.' : 'Mrs.') . '</li>
                <li>Name: ' . $this->applicationInput->getFirstname() . ' ' . $this->applicationInput->getLastname() . '</li>
                <li>Flexible? ' . ($this->applicationInput->getFlexible() == 1 ? 'yes' : 'no') . '</li>
                <li>Address: ' . $this->applicationInput->getStreet() . ' ' . $this->applicationInput->getHouseNumber() . '</li>
                <li>City: ' . $this->applicationInput->getCity() . '</li>
                <li>Country: ' . $this->applicationInput->getCountry() . '</li>
                <li>Dojo:  ' . $this->applicationInput->getDojo() . '</li>
                <li>TWA-number: ' . $this->applicationInput->getTwaNumber() . '</li>
                <li>Grading: ' . $this->applicationInput->getGrading() . ' (since ' . $this->applicationInput->getDateOfLastGrading() . ')</li>
                <li>Room category: ' . $this->applicationInput->getRoom() . '</li>
                <li>together with: ' . $this->applicationInput->getPartnerOne() . '</li>
                <li>and: ' . $this->applicationInput->getPartnerTwo() . '</li>
                <li>Food category: ' . $this->applicationInput->getFoodCategory() . '</li>
                <li>Remarks: ' . $remarks . '</li>
                </ul>
                </p>
                <p>
                Thanks for your patience! We are looking forward to seeing you -<br />
                </p>
                <h3>
                All the best from Berlin<br />
                Benjamin und Philipp</h3>
            </h2>
        </body>
    </html>';

        return $mailtext;
    }

    private static function reformat($input)
    {
        if (!empty($input)) {
            return nl2br($input);
        }
        return "n/a";
    }

    private function setStatusAppliedIfPossible()
    {
        $statusApplied = $this->statusReader->getByName('WAITING_FOR_PAYMENT');
        if (isset($statusApplied)) {
            $this->applicationInput->setCurrentStatus($statusApplied[0]['id']);
        }
    }

    public function saveInDatabase()
    {
        // TODO we need to update!!!
        // * state
        // * paymentrequestedDate
        return $this->dbWriter->persist($this->applicationInput);
    }

    /**
     * Send mails to us after sending a mail to the person that registered.
     */
    public function sendInternally()
    {
        if ($this->config->sendinternalregistrationmails() && !$this->isMailSent()) {

            $replyto = $this->config->registrationmail();
            $importance = 1;

            // As long as https://github.com/ottlinger/hornherzogen/issues/19 is not fixed by goneo:
            $encoded_subject = "=?UTF-8?B?" . base64_encode("Bezahlung Herzogenhorn angefordert - Woche " . $this->applicationInput->getWeek()) . "?=";

            // set all necessary headers to prevent being treated as SPAM in some mailers, headers must not start with a space
            $headers = array();
            $headers[] = 'MIME-Version: 1.0';

            $headers[] = 'X-Priority: ' . $importance;
            $headers[] = 'Importance: ' . $importance;
            $headers[] = 'X-MSMail-Priority: High';

            $headers[] = 'Reply-To: ' . $replyto;
            // https://api.drupal.org/api/drupal/includes%21mail.inc/function/drupal_mail/6.x
            $headers[] = 'From: ' . $replyto;
            $headers[] = 'Return-Path: ' . $replyto;
            $headers[] = 'Errors-To: ' . $replyto;

            $headers[] = 'Content-type: text/html; charset=UTF-8';
            $headers[] = 'Date: ' . date("r");
            $headers[] = 'Message-ID: <' . md5(uniqid(microtime())) . '@' . $_SERVER["SERVER_NAME"] . ">";
            $headers[] = 'X-Git-Revision: <' . $this->revision->gitrevision() . ">";
            $headers[] = 'X-Sender-IP: ' . $_SERVER["REMOTE_ADDR"];
            $headers[] = 'X-Mailer: PHP/' . phpversion();

            mail($replyto, $encoded_subject, $this->getInternalMailtext(), implode("\r\n", $headers), "-f " . $replyto);

            return $this->uiPrefix . $this->localizer->i18nParams('PMAIL.INTERNAL', $this->formHelper->timestamp()) . "</h3>";
        }
        return '';
    }

    public function getInternalMailtext()
    {
        $remarks = self::reformat($this->applicationInput->getRemarks());

        $mailtext =
            '
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Anmeldung für Woche ' . $this->applicationInput->getWeek() . ' eingegangen</title >
        </head>
        <body>
            <h1>Herzogenhorn ' . $this->localizer->i18n('CONST.YEAR') . ' - Anmeldung für Woche ' . $this->applicationInput->getWeek() . '</h1>
            <h2>Anmeldungsdetails</h2>
                <p>es ging gegen ' . $this->formHelper->timestamp() . ' die folgende Anmeldung ein:</p>
                <ul>
                <li>Woche: ' . $this->applicationInput->getWeek() . '</li>
                <li>Anrede: ' . ($this->applicationInput->getGender() === 'male' ? 'Herr' : 'Frau') . '</li>
                <li>Name: ' . $this->applicationInput->getFirstname() . ' ' . $this->applicationInput->getLastname() . '</li>
                <li>interner Name: ' . $this->applicationInput->getFullname() . '</li>
                <li>Umbuchbar? ' . ($this->applicationInput->getFlexible() == 1 ? 'ja' : 'nein') . '</li>
                <li>Adresse: ' . $this->applicationInput->getStreet() . ' ' . $this->applicationInput->getHouseNumber() . '</li>
                <li>Stadt: ' . $this->applicationInput->getCity() . '</li>
                <li>Land: ' . $this->applicationInput->getCountry() . '</li>
                <li>Dojo:  ' . $this->applicationInput->getDojo() . '</li>
                <li>TWA: ' . $this->applicationInput->getTwaNumber() . '</li>
                <li>Graduierung: ' . $this->applicationInput->getGrading() . ' (seit ' . $this->applicationInput->getDateOfLastGrading() . ')</li>
                <li>Zimmerkategorie: ' . $this->applicationInput->getRoom() . '</li>
                <li>Person 1: ' . $this->applicationInput->getPartnerOne() . '</li>
                <li>Person 2: ' . $this->applicationInput->getPartnerTwo() . '</li>
                <li>Essenswunsch: ' . $this->applicationInput->getFoodCategory() . '</li>
                <li>Anmerkungen: ' . $remarks . '</li>
                <li>E-Mail: <a href="' . $this->formHelper->convertToValidMailto($this->applicationInput->getEmail(), $this->config->registrationmail(), "Nachfrage zur Hornanmeldung", "") . '">
                ' . $this->applicationInput->getEmail() . '</a></li>
                </ul>
            </h2>
             das Formular versendet.
            </p>
        </body>
    </html>';

        return $mailtext;
    }

}
