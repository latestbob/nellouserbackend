<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\NelloAccountNotification;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Traits\EclinicClient;
use Illuminate\Support\Str;
use Exception;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;

class ImportEclinicPatientJob implements ShouldQueue
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
        $response = $this->httpGet('/patient-api', []);

        if ($response->getReasonPhrase() === 'OK') {
            $body = (string) $response->getBody();
            $json = json_decode($body, true, 1000);
            $patients = $json['_embedded']['patient'];
            print_r($patients);
            foreach($patients as $patient) {
                $user = User::where('email', $patient['email'])->first();
                if (empty($user)) {
                    echo 'Create new user';
                    $data = [
                        'uuid'       => Str::uuid()->toString(),
                        'firstname'  => $patient['forename'],
                        'lastname'   => $patient['surname'],
                        'middlename' => $patient['middle_name'],
                        'gender'     => $patient['gender'],
                        'email'      => $patient['email'], 
                        'phone'      => $patient['phone'],
                        'password'   => bcrypt(Str::random(8)),
                        'user_type'  => 'customer',
                        'dob'        => Carbon::parse($patient['dob'])->toDateString(),
                        'picture'    => $patient['photo'],
                        'address'    => $patient['address'],
                        'religion'   => $patient['religion'],
                        'eclinic_patient_id' => $patient['patient_id'],
                        'eclinic_upi'        => $patient['upi'],
                    ];
    
                    $user = User::create($data);
                    //SendEclinicOnboardEmail::dispatch($user);

                    //ExportUserJob::dispatch($user);
                    ImportEclinicMedicationsJob::dispatch($patient['medications'], $user);
                    ImportEclinicDrugAllergiesJob::dispatch($patient['drug_allergy'], $user);
                    ImportEclinicPastSurgeriesJob::dispatch($patient['past_surgeries'], $user);
                    ImportEclinicMedicalHistoriesJob::dispatch($patient['medical_history'], $user);
                } 
                else {
                    echo 'Update user';
                    $data = [
                        'eclinic_patient_id' => $patient['patient_id'],
                        'eclinic_upi'        => $patient['upi'],
                    ];
                    $user->update($data);
                }

                ImportEclinicImmunizationsJob::dispatch($patient, $user);
            }
        } else {
            throw new Exception((string)$response->getBody());
        }
    }
}
