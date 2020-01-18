<?php

namespace App\Jobs;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use GuzzleHttp\Psr7; 
use GuzzleHttp\Exception\RequestException;
use App\Traits\GuzzleClient;


class ExportAppointment implements ShouldQueue
{
    use GuzzleClient;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $action;
    private $appointment;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Appointment $appointment, string $action)
    {
        $this->action = $action;
        $this->appointment = $appointment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $this->appointment->load('user.vendor');
        $vendor = $this->appointment->user->vendor;
        $endpoint = '/api/import/appointments';

        if ($this->action == 'create') {
            $response = $this->httpPost($vendor, $endpoint, $this->appointment->toArray());
        }
        else if ($this->action == 'update') {
            $response = $this->httpPut($vendor, $endpoint, $this->user->toArray());
        }
        else if ($this->action == 'delete') {
            $response = $this->httpDelete($vendor, $endpoint, [
                'uuid' => $this->appointment->uuid
            ]);
        }
    }
}
