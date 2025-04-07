<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOrderMail implements ShouldQueue
{
    const ORDER_CONFIRMED = 1;
    const ORDER_PAYMENT_RECEIVED = 2;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $order;
    private $mailType;

    /**
     * Create a new job instance.
     *
     * @param Order $order
     * @param int $mailType
     */
    public function __construct(Order $order, int $mailType)
    {
        $this->order = $order;
        $this->mailType = $mailType;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Throwable
     */
    public function handle()
    {

        $html = view($this->mailType === self::ORDER_CONFIRMED ?
                'mail.order-confirm' : 'mail.order-payment-received', ['order' => $this->order])->render();

        Mail::send([], [], function ($message) use ($html) {
            $message->to($this->order->email);
            $message->setBody($html, 'text/html');
            $message->subject($this->mailType === self::ORDER_CONFIRMED ?
                'Order Confirmation' : 'Order Payment Received');
        });


        $html = view($this->mailType === self::ORDER_CONFIRMED ?
            'mail.order-confirm-admin' : 'mail.order-payment-received-admin', ['order' => $this->order])->render();

        Mail::send([], [], function ($message) use ($html) {
            $message->to("Centre0@famacare.com");
            $message->setBody($html, 'text/html');
            $message->subject($this->mailType === self::ORDER_CONFIRMED ?
                'Order Notification' : 'Order Payment Notification');
        });

        Mail::send([], [], function ($message) use ($html) {
            $message->to("support@asknello.com");
            $message->setBody($html, 'text/html');
            $message->subject($this->mailType === self::ORDER_CONFIRMED ?
                'Order Notification' : 'Order Payment Notification');
        });
    }
}
