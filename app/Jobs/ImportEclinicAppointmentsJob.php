<?php

namespace App\Jobs;

use Illuminate\Support\Str;
use App\Traits\EclinicClient;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ImportEclinicAppointmentsJob implements ShouldQueue
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
        if ($response->getReasonPhrase() == 'OK') {
            
        }   
        else {
            throw new Exception((string) $response->getBody());
        }     
    }
}
