<?php
declare(strict_types = 1);
namespace hornherzogen;

use hornherzogen\db\ApplicantDatabaseWriter;
use hornherzogen\db\StatusDatabaseReader;

class SubmitMailer
{
    // internal members
    private $formHelper;
    private $applicationInput;
    private $revision;
    private $localizer;
    private $config;
    private $dbWriter;
    private $statusReader;

    function __construct($applicationInput)
    {
        $this->applicationInput = $applicationInput;

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

        // HowToSend at all: https://wiki.goneo.de/mailversand_php_cgi

        // Fix encoding errors in subject:
        // http://stackoverflow.com/questions/4389676/email-from-php-has-broken-subject-header-encoding#4389755
        // https://ncona.com/2011/06/using-utf-8-characters-on-an-e-mail-subject/

        // $preferences = ['input-charset' => 'UTF-8', 'output-charset' => 'UTF-8'];
        // $encoded_subject = iconv_mime_encode('Subject', $this->localizer->i18nParams('MAIL.SUBJECT', $this->formHelper->timestamp()), $preferences);
        // $encoded_subject = substr($encoded_subject, strlen('Subject: '));

        // As long as https://github.com/ottlinger/hornherzogen/issues/19 is not fixed by goneo:
        $encoded_subject = "=?UTF-8?B?" . base64_encode($this->localizer->i18nParams('MAIL.SUBJECT', $this->formHelper->timestamp())) . "?=";

        // set all necessary headers to prevent being treated as SPAM in some mailers, headers must not start with a space
        $headers = array();
        $headers[] = 'MIME-Version: 1.0';

        $headers[] = 'X-Priority: ' . $importance;
        $headers[] = 'Importance: ' . $importance;
        $headers[] = 'X-MSMail-Priority: High';

        $headers[] = 'Reply-To: ' . $replyto;
        // https://api.drupal.org/api/drupal/includes%21mail.inc/function/drupal_mail/6.x
        $headers[] = 'From: ' . $replyto;
        $headers[] = 'Sender: ' . $replyto;
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
            $this->applicationInput->setCreatedAt($appliedAt);

            $this->applicationInput->setLanguage($this->formHelper->extractMetadataForFormSubmission()['LANG']);
            $this->setStatusAppliedIfPossible();

            return '<p>Mail abgeschickt um ' . $appliedAt . '</p>';
        }
        $this->applicationInput->setMailSent(true);

        return '';
    }

    private function setStatusAppliedIfPossible() {
        $statusApplied = $this->statusReader->getByName('APPLIED');
        if(isset($statusApplied)) {
            $this->applicationInput->setCurrentStatus($statusApplied[0]['id']);
        }
    }

    public function saveInDatabase() {
        return $this->dbWriter->persist($this->applicationInput);
    }

    public function existsInDatabase() {
        return boolval($this->dbWriter->getByNameAndMailadress($this->applicationInput->getFirstname(), $this->applicationInput->getLastname(), $this->applicationInput->getEmail()));
    }

    public function isMailSent()
    {
        return boolval($this->applicationInput->isMailSent());
    }

    public function getMailtext()
    {
        $remarks = self::reformat($this->applicationInput->getRemarks());

        $metadata = $this->formHelper->extractMetadataForFormSubmission();

        $mailtext =
            '
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Anmeldebestätigung Herzogenhorn Woche ' . $this->applicationInput->getWeek() . ' eingegangen</title >
        </head>
        <body>
            <h1>Herzogenhorn 2017 - Anmeldung für Woche ' . $this->applicationInput->getWeek() . '</h1>
            <h2>
                Hallo ' . $this->applicationInput->getFirstname() . ',</h2>
                <p>wir haben Deine Anmeldedaten für den Herzogenhornlehrgang 2017 um ' . $this->formHelper->timestamp() . '
                erhalten und melden uns sobald die Anmeldefrist abgelaufen ist und wir die beiden Wochen geplant haben.
                </p>
                <p>Deine Anmeldung erfolgte mit den folgenden Eingaben:
                <ul>
                <li>Anrede: ' . $this->applicationInput->getGender() . '</li>
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
                <li>Woche: '.$this->applicationInput->getWeek().'</li>
                </ul>
                </p>
                <p>
                Danke für Deine Geduld und wir freuen uns auf das gemeinsame Training mit Dir und Meister Shimizu-<br />
                </p>
                <h3>
                Bis dahin sonnige Grüße aus Berlin<br />
                von Benjamin und Philipp</h3>
            </h2>
            <p>
            PS: Du hast die Sprache "' . $metadata['LANG'] . '" im Browser "' . $metadata['BROWSER'] . '" ausgewählt
            und von der Adresse "' . $metadata['R_ADDR'] . '"';
        if ($this->formHelper->isSetAndNotEmptyInArray($metadata, "R_HOST")) {
            $mailtext .= '(' . $metadata['R_HOST'] . ')';
        }

        $mailtext .= ' das Formular versendet.
            </p>
        </body>
    </html>';

        return $mailtext;
    }

    private static function reformat($input)
    {
        $remarks = $input;
        if (!empty($remarks)) {
            $remarks = nl2br($remarks);
        } else {
            $remarks = "n/a";
        }
        return $remarks;
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
            $encoded_subject = "=?UTF-8?B?" . base64_encode("Anmeldung Herzogenhorn eingegangen") . "?=";

            // set all necessary headers to prevent being treated as SPAM in some mailers, headers must not start with a space
            $headers = array();
            $headers[] = 'MIME-Version: 1.0';

            $headers[] = 'X-Priority: ' . $importance;
            $headers[] = 'Importance: ' . $importance;
            $headers[] = 'X-MSMail-Priority: High';

            $headers[] = 'Reply-To: ' . $replyto;
            // https://api.drupal.org/api/drupal/includes%21mail.inc/function/drupal_mail/6.x
            $headers[] = 'From: ' . $replyto;
            $headers[] = 'Sender: ' . $replyto;
            $headers[] = 'Return-Path: ' . $replyto;
            $headers[] = 'Errors-To: ' . $replyto;

            $headers[] = 'Content-type: text/html; charset=UTF-8';
            $headers[] = 'Date: ' . date("r");
            $headers[] = 'Message-ID: <' . md5(uniqid(microtime())) . '@' . $_SERVER["SERVER_NAME"] . ">";
            $headers[] = 'X-Git-Revision: <' . $this->revision->gitrevision() . ">";
            $headers[] = 'X-Sender-IP: ' . $_SERVER["REMOTE_ADDR"];
            $headers[] = 'X-Mailer: PHP/' . phpversion();

            mail($replyto, $encoded_subject, $this->getInternalMailtext(), implode("\r\n", $headers), "-f " . $replyto);

            return '<p>Interne Mail an das Organisationsteam abgeschickt um ' . $this->formHelper->timestamp() . '</p>';
        }
        return '';
    }

    public function getInternalMailtext()
    {
        $remarks = self::reformat($this->applicationInput->getRemarks());

        $metadata = $this->formHelper->extractMetadataForFormSubmission();

        $mailtext =
            '
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Anmeldung für Woche ' . $this->applicationInput->getWeek() . ' eingegangen</title >
        </head>
        <body>
            <h1>Herzogenhorn 2017 - Anmeldung für Woche ' . $this->applicationInput->getWeek() . '</h1>
            <h2>Anmeldungsdetails</h2>
                <p>gegen ' . $this->formHelper->timestamp() . ' ging die folgende Anmeldung ein:</p>
                <p>Deine Anmeldung erfolgte mit den folgenden Eingaben:
                <ul>
                <li>Woche: '.$this->applicationInput->getWeek().'</li>
                <li>Anrede: ' . $this->applicationInput->getGender() . '</li>
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
                </ul>
                </p>
            </h2>
            <p>
            PS: Sprache "' . $metadata['LANG'] . '" im Browser "' . $metadata['BROWSER'] . '" ausgewählt
            und von der Adresse "' . $metadata['R_ADDR'] . '"';
        if ($this->formHelper->isSetAndNotEmptyInArray($metadata, "R_HOST")) {
            $mailtext .= '(' . $metadata['R_HOST'] . ')';
        }

        $mailtext .= ' das Formular versendet.
            </p>
        </body>
    </html>';

        return $mailtext;
    }

}
