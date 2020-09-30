<?php

namespace App\Jobs;

use App\Models\CustomerPointRule;
use App\Models\CustomerPoint;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendPointMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $point;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(CustomerPoint $point)
    {
        $this->point = $point;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Throwable
     */
    public function handle()
    {
        $rules = CustomerPointRule::orderByDesc('id')->limit(1)->first();

        if ($rules) {
            $html = view('mail.earned-point', ['rules' => $rules, 'point' => $this->point])->render();
            Mail::send([], [], function ($message) use ($html) {
                $message->to($this->point->user->email);
                $message->setBody($html, 'text/html');
                $message->subject('Nello Point Earned');
            });
        }
    }
}
