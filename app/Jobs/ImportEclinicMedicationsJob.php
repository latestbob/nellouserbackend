<?php

namespace App\Jobs;

use App\Models\Medication;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ImportEclinicMedicationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;
    private $medications;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $medications, User $user)
    {
        $this->user = $user;
        $this->medications = $medications;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach($this->medications as $medication) {
            $data = [
                'uuid' => Str::uuid()->toString(),
                'user_uuid' => $this->user->uuid,
                'drug_name' => $medication['drug_name'],
                'dosage'    => $medication['dosage'],
                'strength'  => $medication['strength'],
                'frequency' => $medication['frequency'],
                'duration'  => $medication['duration'],
                'length'    => $medication['length'],
                'quantity'  => $medication['quantity'],
                'user_id'   => $this->user->id,
            ];

            Medication::create($data);
        }
    }
}
