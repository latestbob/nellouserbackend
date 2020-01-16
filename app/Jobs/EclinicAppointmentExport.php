<?php

namespace App\Jobs;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Traits\EclinicClient;

class EclinicAppointmentExport implements ShouldQueue
{
    use EclinicClient;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    private $appointment;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = [
            "appointment_date" => $this->appointment->date,
            "purpose" => $this->appointment->reason
        ];

        $this->httpPost('/appointment', $data);
    }
}
