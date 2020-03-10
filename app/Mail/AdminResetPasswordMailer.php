<?php

namespace App\Mail;

use App\Models\Admin;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminResetPasswordMailer extends Mailable
{
    use Queueable, SerializesModels;

    private $admin;

    private $password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Admin $admin, string $password)
    {
        $this->admin = $admin;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.admin-reset-password', ['password' => $this->password, 'admin' => $this->admin]);
    }
}
