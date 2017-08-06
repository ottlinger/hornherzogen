<?php

declare(strict_types=1);

namespace hornherzogen\mail;

use hornherzogen\GitRevision;

class MailHeaderGenerator
{
    private $revision;

    public function __construct()
    {
        $this->revision = new GitRevision();
    }

    /**
     * Returns all mail headers for the given $replyTo address to avoid spam classification.
     * Contains meta headers with
     * * PHPVersion
     * * GitRevision if applicable.
     *
     * @param $replyTo
     *
     * @return array
     */
    public function getHeaders($replyTo)
    {
        $importance = 1; //1 UrgentMessage, 3 Normal

        // set all necessary headers to prevent being treated as SPAM in some mailers, headers must not start with a space
        $headers = [];
        $headers[] = 'MIME-Version: 1.0';

        $headers[] = 'X-Priority: '.$importance;
        $headers[] = 'Importance: '.$importance;
        $headers[] = 'X-MSMail-Priority: High';

        $headers[] = 'Reply-To: '.$replyTo;
        // https://api.drupal.org/api/drupal/includes%21mail.inc/function/drupal_mail/6.x
        $headers[] = 'From: '.$replyTo;
        $headers[] = 'Return-Path: '.$replyTo;
        $headers[] = 'Errors-To: '.$replyTo;

        $headers[] = 'Content-type: text/html; charset=UTF-8';
        $headers[] = 'Date: '.date('r');
        $headers[] = 'Message-ID: <'.md5(uniqid(microtime())).'@'.$_SERVER['SERVER_NAME'].'>';
        $headers[] = 'X-Git-Revision: <'.$this->revision->gitrevision().'>';
        $headers[] = 'X-Sender-IP: '.$_SERVER['REMOTE_ADDR'];
        $headers[] = 'X-Mailer: PHP/'.phpversion();

        return $headers;
    }
}
