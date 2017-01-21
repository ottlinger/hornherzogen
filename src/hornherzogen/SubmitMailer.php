<?php namespace hornherzogen;

class SubmitMailer
{

    private $email = 'phil@edojo.org';
    private $firstname = 'Philio';
    private $lastname = 'Egonitschow';

    public function send()
    {
        $subject = 'Hornherzogen Bestätigung';
        $headers = 'From: home@aiki-it.de' . "\r\n" .
            'Bcc: ottlinger@kaishinkan.de' . "\r\n" .
            'Reply-To: otg@aiki-it.de' . "\r\n" .
            'Content-type: text/html; charset=utf-8' . "\r\n" .
            'MIME-Version: 1.0'. "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        mail($this->email, $subject, $this->getMailtext(), $headers);
    }

    public function getMailtext()
    {
        $name = trim($this->firstname . ' ' . $this->lastname);

        return '
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Bestätigung</title >
        </head>
        <body>
            <h1>Horn 2017</h1>
            <p>
                Hi ' . $name . ', you\'ve got mail from Hornherzogenprototype - ' . date('Y-m-d H:i:s') . '
            </p>
        </body>
    </html>';
    }

    /**
     * private function mail_utf8($to, $from_user, $from_email,
     * $subject = '(No subject)', $message = '')
     * {
     * $from_user = "=?UTF-8?B?".base64_encode($from_user)."?=";
     * $subject = "=?UTF-8?B?".base64_encode($subject)."?=";
     *
     * $headers = "From: $from_user <$from_email>\r\n".
     * "MIME-Version: 1.0" . "\r\n" .
     * "Content-type: text/html; charset=UTF-8" . "\r\n";
     *
     * return mail($to, $subject, $message, $headers);
     * }
     */

}

?>