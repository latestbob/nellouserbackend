<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderDelivered extends Mailable
{
    use Queueable, SerializesModels;
    public $deliver;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($deliver)
    {
        //
        $this->deliver = $deliver;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.orderdelivered')->
        from('support@asknello.com')->
        subject('Order Delivered')->
        with('deliver' , $this->deliver);
    }
}
