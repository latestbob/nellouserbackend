<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotPasswordMailer extends Mailable
{
    use Queueable, SerializesModels;

    private $user;

    private $code;

    /**
     * Create a new message instance.
     *
     * ForgotPasswordMailer constructor.
     * @param User $user
     * @param string $code
     */
    public function __construct(User $user, string $code)
    {
        $this->user = $user;
        $this->code = $code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.forgot-password', ['code' => $this->code, 'user' => $this->user]);
    }
}
