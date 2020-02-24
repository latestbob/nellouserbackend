<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Traits\GuzzleClient;

class ImportNelloPatientsJob implements ShouldQueue
{
    use GuzzleClient;
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
        $vendor = Vendor::find(1);
        $response = $this->httpGet($vendor, '/api/nello/patients', []);
        if ($response->getReasonPhrase() == 'OK') {
            $body = (string) $response->getBody();
            $patients = json_decode($body, true, 1000);
            foreach($patients as $patient) {
                unset($patient['role_id']);
                unset($patient['eclinic_upi']);
                unset($patient['eclinic_patient_id']);
                $patient['vendor_id'] = $vendor->id;
                User::create($patient);
            }
        }
    }
}
