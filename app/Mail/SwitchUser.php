<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SwitchUser extends Mailable
{
    use Queueable, SerializesModels;
    public $switchuser;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($switchuser)
    {
        //

        $this->switchuser = $switchuser;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.userswitch')->
        from('support@asknello.com')->
        subject('Nello - Update on your appointment')->
        with('switchuser' , $this->switchuser);
    }
}
