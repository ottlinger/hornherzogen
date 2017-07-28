<?php
declare(strict_types=1);

namespace hornherzogen\mail;

use hornherzogen\Applicant;
use hornherzogen\ConfigurationWrapper;
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
    private $headerGenerator;
    private $stateChanger;
    private $bookingReader;

    // defines how the success messages are being shown in the UI
    private $statusReader;

    function __construct($applicants = NULL)
    {
        $this->applicants = $applicants;
        if (!isset($applicants) && NULL === $applicants) {
            $this->applicants = self::createTestApplicants();
        }

        $this->headerGenerator = new MailHeaderGenerator();
        $this->formHelper = new FormHelper();

        $this->localizer = new HornLocalizer();
        $this->config = new ConfigurationWrapper();
        $this->statusReader = new StatusDatabaseReader();
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
        if (empty($this->applicants)) {
            echo "<h3>Keine Teilnehmer im richtigen Status gefunden! Bitte prüfen.";
            return -1;
        }

        $counter = 0;
        $bookedDBId = $this->statusReader->getByName("BOOKED")[0]['id'];

        if ($this->applicants != NULL) {
            foreach ($this->applicants as $applicant) {
                echo "<h2>Sending out to " . ++$counter . ".applicant with <a href='db_applicant.php?id=" . $applicant->getPersistenceId() . "' target='_blank'>#" . $applicant->getPersistenceId() . "</a> / " . $applicant->getFullName() . "</h2>";

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
        // all non German customers will get an English mail
        if ($applicant->getLanguage() != 'de') {
            return $this->getEnglishMailtext($applicant);
        }

        $twaReminder = '';
        if (NULL != $applicant->getTwaNumber() && strlen($applicant->getTwaNumber())) {
            $twaReminder = '<p>Bitte nicht den twa-Ausweis vergessen!</p>';
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
                <p>Du bist mit dieser Mail final für das Herzogenhorn angemeldet. 
                Aktuell bist Du für den Raum ' . $this->getRoomBookingsInMail($applicant) . ' eingeplant.
                </p>
                <p>
                Bitte beachte die folgenden <b>Stornoregeln</b> und kontaktiere uns im Notfall, sodass Aikidoka auf der Warteliste nachrücken können:
                <ul>
                <li>bis 4 Wochen vor Lehrgangsbeginn kostenfrei</li>
                <li>bis 2 Wochen vor Lehrgangsbeginn 50% der Kosten</li>
                <li>weniger als 2 Wochen vor Lehrgangsbeginn 100% der Kosten</li>
                </ul>
                </p>' . $twaReminder . '
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

        $twaReminder = '';
        if (NULL != $applicant->getTwaNumber() && strlen($applicant->getTwaNumber())) {
            $twaReminder = '<p>Please do not forget your twa-passport!</p>';
        }

        return
            '
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Final confirmation to attend Herzogenhorn ' . $this->localizer->i18n('CONST.YEAR') . ' seminar in week ' . $applicant->getWeek() . '</title >
        </head>
        <body>
            <h1>final confirmation for Herzogenhorn ' . $this->localizer->i18n('CONST.YEAR') . ' - seminar week ' . $applicant->getWeek() . '</h1>
            <h2>
                Hi ' . $applicant->getFirstname() . ',</h2>
                <p>we\'ve received your payment. Thanks for doing it on time!</p>
                <p>Thus we confirm your participation in week ' . $applicant->getWeek() . '.</p>
                <p>Currently your planned accommodation is room ' . $this->getRoomBookingsInMail($applicant) . '</p>
                <p>
                In case you are unable to attend the seminar, feel free to contact us, as people from the waiting list might be eager to attend. Additionally you may cancel your seat under the following <b>compensation restrictions</b>:
                <ul>
                <li>no costs/free - up to 4 weeks to the beginning of the seminar</li>
                <li>50% of the seminar costs - up to 2 weeks to the beginning of the seminar</li>
                <li>100% of the costs - less than 2 weeks to the beginning of the seminar</li>
                </ul>
                </p>' . $twaReminder . '
                <h3>
                All the best from Berlin, have a safe trip to Herzogenhorn :-)<br />
                Benjamin und Philipp</h3>
            </h2>
        </body>
    </html>';
    }

    function getRoomBookingsInMail($applicant)
    {
        if (!isset($applicant)) {
            return "n/a";
        }

        // collect any booked rooms
        $bookings = $this->bookingReader->getForApplicant($applicant->getPersistenceId());

        // start with default no room
        if ('de' == $applicant->getLanguage()) {
            $mailtext = "'unbekannt'";
        } else {
            $mailtext = "'unknown'";
        }

        // add any more rooms
        if (isset($bookings) && !empty($bookings) && NULL != $bookings) {
            $mailtext = '<ul>';
            foreach ($bookings as $b) {
                $mailtext .= '<li>' . $b['name'] . '</li>';
                $mailtext .= '<li>' . $this->localizer->i18nParams('CMAIL.ROOMCAPACITY', $b['capacity']) . '</li>';
            }
            $mailtext .= '</ul>';
        }

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
