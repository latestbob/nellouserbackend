<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserFeeback extends Mailable
{
    use Queueable, SerializesModels;
    public $feedback;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($feedback)
    {
        //

        $this->feedback = $feedback;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.customerfeedback')->
        from('support@asknello.com')->
        subject('Thank you for your feedback on Asknello')->
        with('feedback' , $this->feedback);
    }
}
