<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentDoctor extends Mailable
{
    use Queueable, SerializesModels;
    public $customerdetails;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($customerdetails)
    {
        //
        $this->customerdetails = $customerdetails;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        

        return $this->markdown('mail.appointmentdoctor')->
        from('support@asknello.com')->
        subject('Nello Appointment Scheduled')->
        with('customerdetails' , $this->customerdetails);
    }
}
