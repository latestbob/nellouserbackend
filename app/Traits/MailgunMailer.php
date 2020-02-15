<?php

namespace App\Traits;

use Mailgun\Mailgun;

trait MailgunMailer
{

    /**
     * @param string $to
     * @param string $subject
     * @param string $html
     */
    public function sendMail(string $to, string $subject, string $html)
    {
        $client = Mailgun::create(
            config('mailgun.secret', ''),
            config('mailgun.endpoint', '')
        );

        $domain = config('mailgun.domain', '');
        $from = config('mail.from.name', '') . '<' . config('mail.from.address', '') . '>';

        $params = [
            'from' => $from,
            'to' => $to,
            'subject' => $subject,
            'text' => strip_tags($html),
            'html' => $html
        ];

        $client->messages()->send($domain, $params);
    }

}
