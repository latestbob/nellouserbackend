<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OWCMail extends Mailable
{
    use Queueable, SerializesModels;

    public $owc;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($owc)
    {
        //
        $this->owc = $owc;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        

        return $this->view('email.owccustomer')->
        from('appointment@onewellness.clinic')->
        subject('OWC Appointment Booked')->
        with('owc' , $this->owc);
    }
}
