<?php

use Carbon\Carbon;
use App\Models\User;
use App\Models\Encounter;
use App\Models\Medication;
use App\Models\Vital;
use App\Models\Procedure;
use App\Models\Investigation;
use App\Models\PaystackPayment as Payment;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $patients = [
            [
                'firstname' => 'Roman',
                'lastname' => 'Vlademir',
                'phone' => '08909238292',
                'email' => 'r.vlademir@gmail.com',
                'password' => Hash::make('password'),
                'sponsor' => 'Father',
                'gender' => 'Male',
                'user_type' => 'customer',
                'vendor_id' => 1
            ],
            [
                'firstname' => 'Rosetta',
                'lastname' => 'Chechyn',
                'phone' => '08909238292',
                'email' => 'r.chechyn@gmail.com',
                'password' => Hash::make('password'),
                'sponsor' => 'Mother',
                'gender' => 'Female',
                'user_type' => 'customer',
                'vendor_id' => 1
            ]
        ];

        $encounter = [
            'drug' => 'Tetracycline',
            'test' => 'Sample Test',
            'diagnosis' => 'Negative',
            'note' => 'Taking less sugar should be fine',
            'encounter_date' => Carbon::today()->toDateString(),
            'bms' => 'Sample BMS',
        ];

        $medication = [
            'drug_name' => 'Phenyl',
            'dosage' => '2 tablets daily',
            'strength' => 'powerful stuff',
            'route' => 'Sample route',
            'frequency' => 'Sample frequency',
            'duration' => '3 days',
            'length' => 'Sample length',
            'quantity' => '6 tablets',
            /*'immunization_llin' => 'Sample',
            'immunization_deworming' => 'Sample',
            'immunization_vitamin_A' => 'Sample',
            'immunization_status' => 'Sample Status',
            'immunization_visits' => 'Sample visits',
            'immunization_vaccines' => 'Sample vaccines',*/
        ];

        $vital = [
            'sitting_bp' => 'Sample sitting bp',
            'temperature' => 'sample temperature',
            'weight' => '150',
            'height' => '12',
            'respiration_rate' => 'rate',
            'pulse' => 'sample pulse',
            'vitals_date' => Carbon::today()->toString(),
        ];

        $procedure = [
            'name' => 'Sampling procedure',
            'pdate' => Carbon::today()->toDateString(),
            'result' => 'Results of sampling procedure',
            'note' => 'Notes on sampling prcedure',
        ];

        $investigation = [
            'name' => 'Sampling investigation',
            'idate' => Carbon::today()->toDateString(),
            'result' => 'Results of sampling investigation',
            'result_comment' => 'Comments on results of sampling investigation',
            'note' => 'Notes on sampling investigation',
        ];

        $invoice = [];

        $payment = [
            'status' => 'test',
            'email' => '',
            'amount' => 20000.00,
            'reference' => 'BG563FRAB90',
            'payment_type' => 'card'
        ];

        foreach($patients as $patient) {
            $user = User::create($patient);
            $encounter['user_uuid'] = $user->uuid;
            $encounter['uuid'] = Str::uuid()->toString(); 
            $encounter['session_id'] = Str::uuid()->toString();
            Encounter::create($encounter);
            $medication['user_uuid'] = $user->uuid;
            $medication['uuid'] = Str::uuid()->toString();
            Medication::create($medication); 
            $vital['user_uuid'] = $user->uuid;
            $vital['uuid'] = Str::uuid()->toString(); 
            $vital['session_id'] = Str::uuid()->toString();
            Vital::create($vital);
            $procedure['user_uuid'] = $user->uuid;
            $procedure['uuid'] = Str::uuid()->toString(); 
            Procedure::create($procedure);
            $investigation['user_uuid'] = $user->uuid;
            $investigation['uuid'] = Str::uuid()->toString(); 
            Investigation::create($investigation);
            $payment['email'] = $user->email;
            $payment['user_uuid'] = $user->uuid;
            $payment['uuid'] = Str::uuid()->toString();
            Payment::create($payment);
        }
    }
}
