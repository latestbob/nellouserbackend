<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentMedical extends Mailable
{
    use Queueable, SerializesModels;
    public $medical;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($medical)
    {
        //
        $this->medical = $medical;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.appointmentmedical')->
        from('support@asknello.com')->
        subject('Appointment Booked')->
        with('medical' , $this->medical);
    }
}
