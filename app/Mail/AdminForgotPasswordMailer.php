<?php

namespace App\Mail;

use App\Models\Admin;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminForgotPasswordMailer extends Mailable
{
    use Queueable, SerializesModels;

    private $admin;

    private $code;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Admin $admin, string $code)
    {
        $this->admin = $admin;
        $this->code = $code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.admin-forgot-password', ['code' => $this->code, 'admin' => $this->admin]);
    }
}
