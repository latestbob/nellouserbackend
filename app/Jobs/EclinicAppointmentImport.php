<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Traits\EclinicClient;
use GuzzleHttp\Psr7; 
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;

class EclinicAppointmentImport implements ShouldQueue
{
    use EclinicClient;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $response = $this->httpGet('/appointment', []);
        if ($response->getReasonPhrase() === 'OK') { 
            $data = $response->getBody();
        }

    }
}
