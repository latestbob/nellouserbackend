<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Docreminder extends Mailable
{
    use Queueable, SerializesModels;
    public $docreminder;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($docreminder)
    {
        //
        $this->docreminder = $docreminder;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.docreminder')->
        from('support@asknello.com')->
        subject('Nello Appointment Reminder')->
        with('docreminder' , $this->docreminder);
    }
}
