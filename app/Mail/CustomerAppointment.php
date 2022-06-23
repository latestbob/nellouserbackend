<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerAppointment extends Mailable
{
    use Queueable, SerializesModels;
    
//public $customermail;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //

        //$this->customermail = $customermail;
        
        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.customerappoint')->
        from('support@asknello.com') ->
        subject('Doctor Appointment Booked');
    }
}
