<?php

namespace App\Jobs;

use App\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ContactMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $contactMessage;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ContactMessage $contactMessage)
    {
        $this->contactMessage = $contactMessage;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Throwable
     */
    public function handle()
    {
        $html = view('mail.contact-message', ['contact' => $this->contactMessage])->render();
        Mail::send([], [], function ($message) use ($html) {
//            $message->to("hello@asknello.com");
            $message->to("wisdomemenike70@gmail.com");
            $message->setBody($html, 'text/html');
            $message->subject($this->contactMessage->subject);
        });
    }
}
