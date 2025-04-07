<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminFeedback extends Mailable
{
    use Queueable, SerializesModels;
    public $adminfeedback;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($adminfeedback)
    {
        //

        $this->adminfeedback = $adminfeedback;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.adminfeedback')->
        from('support@asknello.com')->
        subject('New Feedback Received')->
        with('adminfeedback' , $this->adminfeedback);
    }
}
