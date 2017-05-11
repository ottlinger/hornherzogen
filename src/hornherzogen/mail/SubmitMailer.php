<?php
declare(strict_types=1);

namespace hornherzogen\mail;

use hornherzogen\ConfigurationWrapper;
use hornherzogen\db\ApplicantDatabaseWriter;
use hornherzogen\db\StatusDatabaseReader;
use hornherzogen\FormHelper;
use hornherzogen\HornLocalizer;

class SubmitMailer
{
    // internal members
    public $uiPrefix = "<h3 style='color: rebeccapurple; font-weight: bold;'>";
    private $formHelper;
    private $applicationInput;
    private $localizer;
    private $config;
    private $dbWriter;
    private $headerGenerator;

    // defines how the success messages are being shown in the UI
    private $statusReader;

    function __construct($applicationInput)
    {
        $this->applicationInput = $applicationInput;

        $this->formHelper = new FormHelper();
        $this->headerGenerator = new MailHeaderGenerator();
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
        $replyTo = $this->config->registrationmail();
        $headers = $this->headerGenerator->getHeaders($replyTo);

        // HowToSend at all: https://wiki.goneo.de/mailversand_php_cgi

        // Fix encoding errors in subject:
        // http://stackoverflow.com/questions/4389676/email-from-php-has-broken-subject-header-encoding#4389755
        // https://ncona.com/2011/06/using-utf-8-characters-on-an-e-mail-subject/

        // $preferences = ['input-charset' => 'UTF-8', 'output-charset' => 'UTF-8'];
        // $encoded_subject = iconv_mime_encode('Subject', $this->localizer->i18nParams('MAIL.SUBJECT', $this->formHelper->timestamp()), $preferences);
        // $encoded_subject = substr($encoded_subject, strlen('Subject: '));

        // As long as https://github.com/ottlinger/hornherzogen/issues/19 is not fixed by goneo:
        $encoded_subject = "=?UTF-8?B?" . base64_encode($this->localizer->i18nParams('MAIL.SUBJECT', $this->formHelper->timestamp())) . "?=";

        if ($this->config->sendregistrationmails() && !$this->isMailSent()) {
            $mailResult = mail($this->applicationInput->getEmail(), $encoded_subject, $this->getMailtext(), implode("\r\n", $headers), "-f " . $replyTo);
            $appliedAt = $this->formHelper->timestamp();
            $this->applicationInput->setCreatedAt($appliedAt);

            $this->applicationInput->setLanguage($this->formHelper->extractMetadataForFormSubmission()['LANG']);
            $this->setStatusAppliedIfPossible();

            return $this->uiPrefix . $this->localizer->i18nParams('MAIL.APPLICANT', $appliedAt . " (returnCode: " . $mailResult . ")") . "</h3>";
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

        $metadata = $this->formHelper->extractMetadataForFormSubmission();

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
                <p>wir haben Deine Anmeldedaten für den Herzogenhornlehrgang ' . $this->localizer->i18n('CONST.YEAR') . ' um ' . $this->formHelper->timestamp() . '
                erhalten und melden uns sobald die Anmeldefrist abgelaufen ist und wir die beiden Wochen geplant haben.
                </p>
                <p>Deine Anmeldung erfolgte mit den folgenden Eingaben:
                <ul>
                <li>Anrede: ' . ($this->applicationInput->getGender() === 'male' ? 'Herr' : 'Frau') . '</li>
                <li>Name: ' . $this->applicationInput->getFirstname() . ' ' . $this->applicationInput->getLastname() . '</li>
                <li>Umbuchbar? ' . ($this->applicationInput->getFlexible() == 1 ? 'ja' : 'nein') . '</li>
                <li>Adresse: ' . $this->applicationInput->getStreet() . ' ' . $this->applicationInput->getHouseNumber() . '</li>
                <li>Stadt: ' . $this->applicationInput->getCity() . '</li>
                <li>PLZ: ' . $this->applicationInput->getZipCode() . '</li>
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

    private function getEnglishMailtext()
    {
        $remarks = self::reformat($this->applicationInput->getRemarks());

        $metadata = $this->formHelper->extractMetadataForFormSubmission();

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
                <li>Zipcode: ' . $this->applicationInput->getZipCode() . '</li>
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
            <p>
            PS: Your selected language was "' . $metadata['LANG'] . '" with browser "' . $metadata['BROWSER'] . '".
            Submission happened from address "' . $metadata['R_ADDR'] . '"';
        if ($this->formHelper->isSetAndNotEmptyInArray($metadata, "R_HOST")) {
            $mailtext .= '(' . $metadata['R_HOST'] . ')';
        }

        $mailtext .= '
            </p>
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
        $statusApplied = $this->statusReader->getByName('APPLIED');
        if (isset($statusApplied)) {
            $this->applicationInput->setCurrentStatus($statusApplied[0]['id']);
        }
    }

    public function saveInDatabase()
    {
        return $this->dbWriter->persist($this->applicationInput);
    }

    public function existsInDatabase()
    {
        $existingRows = $this->dbWriter->getByNameAndMailadress($this->applicationInput->getFirstname(), $this->applicationInput->getLastname(), $this->applicationInput->getEmail());

        // case1: database contains someone with the same name and mail address - treat as resubmit and do not persist
        if (isset($existingRows) && sizeof($existingRows) == 1 && $existingRows[0]->getFullName() === ($existingRows[0]->getFirstname() . ' ' . $existingRows[0]->getLastname())) {
            return true;
        }

        // case2: there are already more than one entries in the database, persist will correct the current one
        if (sizeof($existingRows) >= 1) {
            return false; // persist is going to correct the doublette by adding a salt to the fullname
        }

        return boolval($existingRows);
    }

    /**
     * Send mails to us after sending a mail to the person that registered.
     */
    public function sendInternally()
    {
        if ($this->config->sendinternalregistrationmails() && !$this->isMailSent()) {

            // As long as https://github.com/ottlinger/hornherzogen/issues/19 is not fixed by goneo:
            $encoded_subject = "=?UTF-8?B?" . base64_encode("Anmeldung Herzogenhorn eingegangen - Woche " . $this->applicationInput->getWeek()) . "?=";

            $replyTo = $this->config->registrationmail();
            $headers = $this->headerGenerator->getHeaders($replyTo);

            $mailResult = mail($replyTo, $encoded_subject, $this->getInternalMailtext(), implode("\r\n", $headers), "-f " . $replyTo);

            return $this->uiPrefix . $this->localizer->i18nParams('MAIL.INTERNAL', $this->formHelper->timestamp() . " (returnCode: " . $mailResult . ")") . "</h3>";
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
                <li>PLZ: ' . $this->applicationInput->getZipCode() . '</li>
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