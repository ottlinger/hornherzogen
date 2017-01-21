<?php
// allow debugging
ini_set('display_errors', 'on');
ini_set('display_startup_errors', 'on');
error_reporting(E_ALL);

// Please rename this file to config.php in your live setup and adapt all values properly
// contact mail

$cfg_mail = 'foo@bar.de';
// location of the seminar announcement
$cfg_pdf = 'https://example.com/data/any.pdf';
// where to send registration data to
$cfg_registrationmail = 'foo@buzz.de';
// Configuration to send out mails
$cfg_mail_smtp = 'smtp.example.com:443';
$cfg_mail_user = 'username';
$cfg_mail_pw = 'mypassword';
?>
