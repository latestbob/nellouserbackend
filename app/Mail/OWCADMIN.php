<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OWCADMIN extends Mailable
{
    use Queueable, SerializesModels;
    public $owcadmin;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($owcadmin)
    {
        //
        $this->owcadmin = $owcadmin;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.toadmin')->
        from('appointment@onewellness.clinic')->
        subject('New OWC Appointment Booked')->
        with('owcadmin' , $this->owcadmin);
    }
}
