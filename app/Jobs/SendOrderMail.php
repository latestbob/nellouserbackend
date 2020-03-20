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
    use MailgunMailer;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $order;
    private $emailAddress;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order, string $email)
    {
        $this->order = $order;
        $this->emailAddress = $email;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Throwable
     */
    public function handle()
    {
        $html = view('mail.order', ['order' => $this->order])->render();
        $this->sendMail($this->emailAddress, 'Order Confirmation', $html);
    }
}
