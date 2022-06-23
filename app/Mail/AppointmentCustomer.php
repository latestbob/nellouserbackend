<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentCustomer extends Mailable
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
        return $this->markdown('mail.appointmentcustomer')->
        from('support@asknello.com')->
        subject('Appointment Booked')->
        with('customerdetails' , $this->customerdetails);
    }
}
