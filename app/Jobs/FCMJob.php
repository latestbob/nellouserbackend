<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Traits\FirebaseNotification;

class FCMJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, FirebaseNotification;

    private $tokens;
    private $title;
    private $body;
    private $payload;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $tokens, string $title, string $body, array $payload)
    {
        $this->title = $title;
        $this->body = $body;
        $this->tokens = $tokens;
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $resp = $this->sendNotification(
            $this->tokens,
            $this->title,
            $this->body,
            'high',
            $this->payload
        );

        print_r($resp);
    }
}
