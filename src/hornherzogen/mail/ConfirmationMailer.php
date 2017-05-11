<?php
declare(strict_types=1);

namespace hornherzogen\mail;

use hornherzogen\admin\BankingConfiguration;
use hornherzogen\Applicant;
use hornherzogen\ConfigurationWrapper;
use hornherzogen\db\ApplicantDatabaseReader;
use hornherzogen\db\ApplicantStateChanger;
use hornherzogen\db\BookingDatabaseReader;
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
    private $bookingReader;

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
        $this->bookingReader = new BookingDatabaseReader();

        date_default_timezone_set('Europe/Berlin');
    }

    public static function createTestApplicants()
    {
        $testApplicant = new Applicant();
        $testApplicant->setFirstname("Emil");
        $testApplicant->setLastname("Mustermann");
        $testApplicant->setTwaNumber("CC-0815");
        $testApplicant->setPersistenceId(self::TEST_APPLICANT);
        $testApplicant->setWeek(2);
        $testApplicant->setLanguage('de');
        $testApplicant->setBookedAt(NULL); // to avoid not being caught in SQL - reset in DB: update applicants set booked = null where statusId =6;
        $testApplicant->setCurrentStatus(6); // PAID
        $testApplicant->setEmail("shouldnotwork"); // do not really send an email
        return array($testApplicant);
    }

    public function sendAsBatch()
    {
        $counter = 1;
        $bookedDBId = $this->statusReader->getByName("BOOKED")[0]['id'];

        if (empty($this->applicants)) {
            echo "<h3>Keine Teilnehmer im richtigen Status gefunden! Bitte prüfen.";
        }

        if ($this->applicants != NULL) {

            foreach ($this->applicants as $applicant) {
                echo "<h2>Sending out to " . $counter++ . ".applicant with <a href='db_applicant.php?id=" . $applicant->getPersistenceId() . "' target='_blank'>#" . $applicant->getPersistenceId() . "</a> / " . $applicant->getFullName() . "</h2>";

                // get a fresh timestamp
                $this->formHelper = new FormHelper();

                $mailResult = $this->send($applicant);
                echo $mailResult;

                if (boolval($mailResult)) {
                    echo "Changing state in database to 'BOOKED' resulted in " . boolval($this->stateChanger->changeStateTo($applicant->getPersistenceId(), $bookedDBId));
                } else {
                    echo "Problem while trying to send mail, will not send out mail. Please try again in 1.5hours time since the overall mail limit of the ISP may have been reached.";
                }
                echo "<hr/>";
            }
        }

        return $counter;
    }

    function send($applicant)
    {
        if (!isset($applicant)) {
            return 'Nothing to send.';
        }

        $replyto = $this->config->registrationmail();
        $headers = $this->headerGenerator->getHeaders($replyto);
        // CC to us internally to avoid ISP blocking
        $headers[] = 'Bcc: ' . $replyto;

        $withParam = new MessageFormatter($applicant->getLanguage(), $GLOBALS['messages']['' . $applicant->getLanguage()]["CMAIL.SUBJECT"]);
        $subject = $withParam->format(array($this->formHelper->timestamp()));

        // we need the key directly for the language of the applicant!
        $encoded_subject = "=?UTF-8?B?" . base64_encode($subject) . "?=";

        if ($this->config->sendregistrationmails()) {
            $mailResult = mail($applicant->getEmail(), $encoded_subject, $this->getMailtext($applicant), implode("\r\n", $headers), "-f " . $replyto);
            $appliedAt = $this->formHelper->timestamp();

            if (!$mailResult) {
                return FALSE;
            }

            return $this->getColouredUIPrefix($mailResult) . $this->localizer->i18nParams('CMAIL.APPLICANT', $appliedAt . " returnCode:" . $mailResult) . "</h3>";
        }

        // if no mail is sent due to configuration everything is fine
        return TRUE;
    }

    public function getMailtext($applicant)
    {
        // collect any booked rooms
        $bookings = $this->bookingReader->getForApplicant($applicant->getPersistenceId());

        // all non German customers will get an English mail
        if ($applicant->getLanguage() != 'de') {
            return $this->getEnglishMailtext($applicant, $bookings);
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
                <p>Du bist mit dieser Mail final für das Herzogenhorn angemeldet. Aktuell bist Du für den Raum ';

        if (!isset($bookings) || empty($bookings) || NULL == $bookings) {
            $mailtext .= "'unbekannt'";
        } else {
            $mailtext .= '<ul>';
            foreach ($bookings as $b) {
                $mailtext .= '<li>' . $b['name'] . '</li>';
            }
            $mailtext .= '</ul>';
        }

        $mailtext .= ' eingeplant.
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

    public function getEnglishMailtext($applicant, $roombookings)
    {
        $mailtext =
            '
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Final confirmation for Herzogenhorn seminar week ' . $applicant->getWeek() . '</title >
        </head>
        <body>
            <h1>Herzogenhorn ' . $this->localizer->i18n('CONST.YEAR') . ' - request for payment seminar week ' . $applicant->getWeek() . '</h1>
            <h2>
                Hi ' . $applicant->getFirstname() . ',</h2>
                <p>TODO add text and room ' . $roombookings . '
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
}
