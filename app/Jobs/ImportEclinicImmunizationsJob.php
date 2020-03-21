<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Immunization;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ImportEclinicImmunizationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;
    private $patient;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $patient, User $user)
    {
        $this->user = $user;
        $this->patient = $patient;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = $this->user;
        $patient = $this->patient;
        $imm = Immunization::where('user_uuid', $user->uuid)->first();
        if (empty($imm)) {
            $data = [
                'user_id'   => $user->id,
                'user_uuid' => $user->uuid,
                'immunization_llin' => $patient['immunization_llin'],
                'immunization_deworming' => $patient['immunization_deworming'],
                'immunization_vitamin_A' => $patient['immunization_vitamin_A'],
                'immunization_status'    => $patient['immunization_status'],
                'immunization_visits'    => $patient['immunization_visits'],
                'immunization_vaccines'  => $patient['immunization_vaccines']
            ];

            Immunization::create($data);
        }
    }
}
