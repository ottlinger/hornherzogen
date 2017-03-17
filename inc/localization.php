<?php

/**
 * Add l10n keys here per language ....
 */

/* Constants */
$h_year = 2017;
$h_applicationPublishedSince = "2017-03-20";
$h_applicationEndDate = "2017-04-29"; // move into configuration! when working on #40

$GLOBALS['messages'] = array();

// GERMAN with constants
$GLOBALS['messages']['de'] = array(
    'CONST.YEAR' => ''.$h_year,
    'MAIL.INTERNAL' => 'Interne Mail an das Organisationsteam abgeschickt um {0}',
    'MAIL.APPLICANT' => 'Mail abgeschickt um {0}',
    'INDEX.EN_CONTINUE' => 'Go to the English version of the registration',
    'INDEX.DE_CONTINUE' => 'Weiter zur Anmeldung',
    'INDEX.LINE1' => 'Auf diesen Seiten kann man sich für den Herzogenhornlehrgang '.$h_year.' anmelden.',
    'INDEX.LINE2' => 'Die Anmeldung läuft über die Berliner Aikidoka.',
    'INDEX.TENDERLINK' => 'Die Ausschreibung findet sich <a href="{0}" target="_blank">hier</a>',
    'CONTACT.HEADER' => 'Herzogenhorn '.$h_year.' - Fragen',
    'CONTACT.H.APPLICATION' => 'Anmeldung',
    'CONTACT.H.APPLICATION.LINE1' => 'Die Ausschreibung gibt es seit '.$h_applicationPublishedSince.',',
    'CONTACT.H.APPLICATION.LINE2' => 'bitte für die jeweilige Lehrgangswoche <a href="{0}">elektronisch</a> anmelden und die E-Mail-Bestätigung abwarten.',
    'CONTACT.H.CONFIRMATION' => 'Buchungsbestätigung',
    'CONTACT.H.CONFIRMATION.LINE1' => 'Sobald die Ausschreibungsfrist zum '.$h_applicationEndDate.' endet,',
    'CONTACT.H.CONFIRMATION.LINE2' => 'senden wir die Anmeldebestätigungen mit Zahlungsaufforderung zu.',
    'CONTACT.H.PAYMENT' => 'Zahlungseingang',
    'CONTACT.H.PAYMENT.LINE1' => 'Sobald die Zahlung der Lehrgangsgebühr eingegangen ist,',
    'CONTACT.H.PAYMENT.LINE2' => 'übersenden wir eine Rechnung und die finale Teilnahmebestätigung.',
    'CONTACT.H.OTHER' => 'Noch Fragen? Lob oder Kritik?',
    'CONTACT.H.OTHER.LINE1' => 'Um Benjamin oder Philipp vom Organisationsteam zu kontaktieren,',
    'CONTACT.H.OTHER.LINE2' => 'am einfachsten eine <a href="mailto:{0}">Mail</a> schreiben.',
    'NAV.TOGGLE' => 'Navigation ein/ausklappen',
    'INDEX.TITLE' => 'Herzogenhorn '.$h_year.' - Startseite',
    'INDEX.WELCOME' => 'Herzlich Willkommen',
    'FORM.TITLE' => 'Herzogenhorn '.$h_year.' - Anmeldung',
    'FORM.MANDATORYFIELDS' => 'Alle mit Stern (*) markierten Felder sind Pflichtfelder und müssen angegeben werden.',
    'FORM.SUBMIT' => 'Formular absenden',
    'FORM.RESET' => 'Alle Eingaben löschen',
    'FORM.SAVEDAS' => 'Erfolgreich gespeichert als Nummer #{0}.',
    'FORM.INTRO.LINE1' => 'Bitte das Formular ausfüllen und absenden',
    'FORM.INTRO.LINE2' => 'und die Bestätigungsmail abwarten.',
    'FORM.SUC.CHECK' => 'Bitte prüfe Dein Mailfach, die Bestätigungsmail wurde erfolgreich versendet.',
    'FORM.REMARK.PL' => 'Bitte gern optional Anmerkungen hinterlassen.',
    'FORM.REMARK' => 'Anmerkungen / Wünsche / Besonderheiten (max. 400 Zeichen):',
    'FORM.OTHER' => 'Sonstiges',
    'FORM.FOOD' => 'Essenswunsch (*)',
    'FORM.FOOD.MEAT' => 'normale Kost (mit Fleisch)',
    'FORM.FOOD.VEG' => 'vegetarische Kost (ohne Fleisch)',
    'FORM.PARTNER.PL' => 'Bitte den kompletten Namen angeben.',
    'FORM.PARTNER.P1' => 'Name Person 1',
    'FORM.PARTNER.P2' => 'Name Person 2',
    'FORM.PARTNER' => 'Bitte Zusammenlegungswünsche angeben (optional) - mit wem soll das Zimmer geteilt werden?',
    'FORM.ROOM.PL' => 'Daten zur Unterkunft',
    'FORM.ROOM' => 'Bitte die Zimmerkategorie festlegen und Zusammenlegungswünsche angeben (*)',
    'FORM.ROOM.3' => '3-Bett Zimmer',
    'FORM.ROOM.2' => '2-Bett Zimmer',
    'FORM.ROOM.1' => 'Einzelzimmer',
    'FORM.GSINCE' => 'Bitte angeben, seit wann die aktuelle Graduierung besteht. (*)',
    'FORM.GRADING' => 'Aktuelle Graduierung (*)',
    'FORM.TWA.PL' => 'Bitte die komplette twa-Mitgliedsnummer angeben (z.B. DE-0815) insofern vorhanden. Hinweis: Nichtmitglieder zahlen mehr!',
    'FORM.TWA' => 'Mitgliedsnummer (twa)',
    'FORM.TWA.LABEL' => 'Ohne gültige twa-Mitgliedschaft erhöht sich der Preis des Lehrgangs um 50,00 €.',
    'FORM.AIKIDO' => 'Aikidodaten',
    'FORM.DOJO' => 'Dojo / Stadt (*)',
    'FORM.DOJO.PL' => 'In welchem Dojo trainierst Du bzw. in welcher Stadt?',


    'MENU.MAIN' => 'Herzogenhorn '.$h_year,
    'MENU.APPLY' => 'Anmeldung',
    'MENU.FAQ' => 'Fragen',
    'MENU.ADMIN' => 'Admin-Bereich',
    'MAIL.SUBJECT' => 'Anmeldung für den 合気道-Lehrgang auf dem Herzogenhorn 2017 - eingegangen {0}',
    'FORM.ERROR_MESSAGE' => 'Es gibt noch {0} Felder mit Fehlern - bitte korrigieren und erneut absenden!',
    'TIME' => 'Es ist {0}');

// ENGLISH
$GLOBALS['messages']['en'] = array(
    'MAIL.INTERNAL' => 'Your data was properly sent to the organisation team (at {0})',
    'MAIL.APPLICANT' => 'A confirmation mail has been sent to you at {0}',
    'INDEX.EN_CONTINUE' => 'Go to the English version of the registration',
    'INDEX.DE_CONTINUE' => 'Weiter zur Anmeldung',
    'INDEX.LINE1' => 'These pages allow to register for '.$h_year.'\'s Herzogenhorn seminar',
    'INDEX.LINE2' => 'This year\'s invitation is organized by the Aikidoka from Berlin (Germany).',
    'INDEX.TENDERLINK' => 'The seminar invitation can be found <a href="{0}" target="_blank">here</a>',
    'CONTACT.HEADER' => 'Herzogenhorn '.$h_year.' - Q&A',
    'CONTACT.H.APPLICATION' => 'Application',
    'CONTACT.H.APPLICATION.LINE1' => 'The official seminar invitation is available since '.$h_applicationPublishedSince.',',
    'CONTACT.H.APPLICATION.LINE2' => 'please do register <a href="{0}">electronically</a> for the week you prefer and await the email confirmation.',
    'CONTACT.H.CONFIRMATION' => 'Booking Confirmation',
    'CONTACT.H.CONFIRMATION.LINE1' => 'As soon as the deadline for applications is reached (after '.$h_applicationEndDate.'),',
    'CONTACT.H.CONFIRMATION.LINE2' => 'all participants/applicants will get a confirmation mail and a request for payment.',
    'CONTACT.H.PAYMENT' => 'Payment Receipt',
    'CONTACT.H.PAYMENT.LINE1' => 'As soon as we\'ve received your payment,',
    'CONTACT.H.PAYMENT.LINE2' => 'we\'ll send you an invoice and a final confirmation.',
    'CONTACT.H.OTHER' => 'Any questions? Praise? Criticism?',
    'CONTACT.H.OTHER.LINE1' => 'In order to contact Benjamin or Philipp from the organisation team,',
    'CONTACT.H.OTHER.LINE2' => 'feel free to send an <a href="mailto:{0}">email</a>.',
    'NAV.TOGGLE' => 'Toggle navigation',
    'INDEX.TITLE' => 'Herzogenhorn '.$h_year.' - main page',
    'INDEX.WELCOME' => 'Welcome',
    'FORM.TITLE' => 'Herzogenhorn '.$h_year.' registration',
    'FORM.MANDATORYFIELDS' => 'All fields marked with an asterisk (*) are mandatory.',
    'FORM.SUBMIT' => 'Submit your booking',
    'FORM.RESET' => 'Reset all your input and start over',
    'FORM.SAVEDAS' => 'Your application was saved as #{0}.',
    'FORM.INTRO.LINE1' => 'Please fill in the form and submit your data.',
    'FORM.INTRO.LINE2' => 'After that a confirmation mail will be sent to you.',
    'FORM.SUC.CHECK' => 'Please check your mailbox, a confirmation has been sent to you.',
    'FORM.REMARK.PL' => 'You may add additional remarks here.',
    // TODO add keys and translate from here
    'MENU.MAIN' => 'Herzogenhorn '.$h_year,
    'MENU.APPLY' => 'Apply form',
    'MENU.FAQ' => 'Questions',
    'MENU.ADMIN' => 'Admin area',
    'MAIL.SUBJECT' => 'Application 合気道 seminar Herzogenhorn 2017 - registered at {0}',
    'FORM.ERROR_MESSAGE' => 'There are {0} input fields with errors, please correct them and resubmit your application!',
    'TIME' => 'Now it is {0}');

// RUSSIAN (just for the sake of UTF-8)
$GLOBALS['messages']['ru'] = array(
    'FORM.TITLE' => 'Herzogenhorn '.$h_year.' заявка',
    'TIME' => 'Сегодня {0}');

// JAPANESE (just for the sake of UTF-8)
$GLOBALS['messages']['jp'] = array(
    'FORM.TITLE' => 'Herzogenhorn '.$h_year.' 登録',
    'TIME' => '今日 {0}');