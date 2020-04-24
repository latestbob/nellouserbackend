<?php

namespace App\Jobs;

use App\Models\Order;
use App\Traits\MailgunMailer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendOrderMail implements ShouldQueue
{
    const ORDER_CONFIRMED = 1;
    const ORDER_PAYMENT_RECEIVED = 2;

    use MailgunMailer;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $order;
    private $emailAddress;
    private $mailType;

    /**
     * Create a new job instance.
     *
     * @param Order $order
     * @param string $email
     * @param int $mailType
     */
    public function __construct(Order $order, string $email, int $mailType)
    {
        $this->order = $order;
        $this->emailAddress = $email;
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
        if ($this->mailType === self::ORDER_CONFIRMED) {
            $html = view('mail.order-confirm', ['order' => $this->order])->render();
        } else {
            $html = view('mail.order-payment-received', ['order' => $this->order])->render();
        }

        $this->sendMail($this->emailAddress, $this->mailType === self::ORDER_CONFIRMED ?
            'Order Confirmation' : 'Order Payment Received', $html);

        if ($this->mailType === self::ORDER_CONFIRMED) {
            $html = view('mail.order-confirm-admin', ['order' => $this->order])->render();
        } else {
            $html = view('mail.order-payment-received-admin', ['order' => $this->order])->render();
        }

        $this->sendMail("orders@famacare.com", $this->mailType === self::ORDER_CONFIRMED ?
            'Order Notification' : 'Order Payment Notification', $html);
    }
}
