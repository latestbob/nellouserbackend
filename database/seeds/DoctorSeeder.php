<?php

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $data = [
            [
                'uuid'      => Str::uuid()->toString(),
                'firstname' => 'JOY',
                'middlename' => 'FLORENCE',
                'lastname' => 'AKHUETIE',
                'phone' => '08034015170',
                'email' => 'drjoy@asknello.com',
                'password' => Hash::make(Str::random(8)),
                'picture' => 'https://res.cloudinary.com/dq1zd0mue/image/upload/v1577575183/doctor2_lilgnf.png',
                //'ufield' => '',
                'aos' => 'OPTOMETRIST',
                'gender' => 'Female',
                'user_type' => 'doctor',
                'vendor_id' => 1
            ],
            [
                'uuid'      => Str::uuid()->toString(),
                'firstname' => 'ADAUGO',
                'middlename' => '',
                'lastname' => 'ADAUGO',
                'phone' => '08036347154',
                'email' => 'dradaugo@asknello.com',
                'password' => Hash::make(Str::random(8)),
                'picture' => 'https://res.cloudinary.com/dq1zd0mue/image/upload/v1577575183/doctor2_lilgnf.png',
                //'ufield' => '',
                'aos' => 'PAEDIATRICIAN',
                'gender' => 'Female',
                'user_type' => 'doctor',
                'vendor_id' => 1
            ],
            [
                'uuid'      => Str::uuid()->toString(),
                'firstname' => 'ADEBAYO',
                'middlename' => '',
                'lastname' => 'ADEBAYO',
                'phone' => '08033339347',
                'email' => 'dradebayo@asknello.com',
                'password' => Hash::make(Str::random(8)),
                'picture' => 'https://res.cloudinary.com/dq1zd0mue/image/upload/v1577575183/doctor2_lilgnf.png',
                //'ufield' => '',
                'aos' => '',
                'gender' => 'Male',
                'user_type' => 'doctor',
                'vendor_id' => 1
            ],
            [
                'uuid'      => Str::uuid()->toString(),
                'firstname' => 'JAIYEOLA',
                'middlename' => '',
                'lastname' => 'JAIYEOLA',
                'phone' => '07060930175',
                'email' => 'drjaiyeola@asknello.com',
                'password' => Hash::make(Str::random(8)),
                'picture' => 'https://res.cloudinary.com/dq1zd0mue/image/upload/v1577575183/doctor2_lilgnf.png',
                //'ufield' => '',
                'aos' => 'DERMATOLOGIST',
                'gender' => '',
                'user_type' => 'doctor',
                'vendor_id' => 1
            ],
            [
                'uuid'      => Str::uuid()->toString(),
                'firstname' => 'EDEMA',
                'middlename' => '',
                'lastname' => 'EDEMA',
                'phone' => '08165250071',
                'email' => 'dredema@asknello.com',
                'password' => Hash::make(Str::random(8)),
                'picture' => 'https://res.cloudinary.com/dq1zd0mue/image/upload/v1577575183/doctor2_lilgnf.png',
                //'ufield' => '',
                'aos' => 'DENTIST',
                'gender' => '',
                'user_type' => 'doctor',
                'vendor_id' => 1
            ],
            [
                'uuid'      => Str::uuid()->toString(),
                'firstname' => 'Ehisemuje',
                'middlename' => '',
                'lastname' => 'Ehisemuje',
                'phone' => '08023131760',
                'email' => 'drehisemuje@asknello.com',
                'password' => Hash::make(Str::random(8)),
                'picture' => 'https://res.cloudinary.com/dq1zd0mue/image/upload/v1577575183/doctor2_lilgnf.png',
                //'ufield' => '',
                'aos' => 'Paediatric Surgeon',
                'gender' => '',
                'user_type' => 'doctor',
                'vendor_id' => 1
            ],
            [
                'uuid'      => Str::uuid()->toString(),
                'firstname' => 'Yemi',
                'middlename' => '',
                'lastname' => 'Johnson',
                'phone' => '09030000999',
                'email' => 'dryemi@asknello.com',
                'password' => Hash::make(Str::random(8)),
                'picture' => 'https://res.cloudinary.com/dq1zd0mue/image/upload/v1577575183/doctor2_lilgnf.png',
                //'ufield' => '',
                'aos' => 'Cardiologist',
                'gender' => '',
                'user_type' => 'doctor',
                'vendor_id' => 1
            ],
            [
                'uuid'      => Str::uuid()->toString(),
                'firstname' => 'Kofo',
                'middlename' => '',
                'lastname' => 'Ogunyankin',
                'phone' => '08091351400',
                'email' => 'drkofo@asknello.com',
                'password' => Hash::make(Str::random(8)),
                'picture' => 'https://res.cloudinary.com/dq1zd0mue/image/upload/v1577575183/doctor2_lilgnf.png',
                //'ufield' => '',
                'aos' => 'Cardiologist',
                'gender' => '',
                'user_type' => 'doctor',
                'vendor_id' => 1
            ],
            [
                'uuid'      => Str::uuid()->toString(),
                'firstname' => 'Wallace',
                'middlename' => '',
                'lastname' => 'Ogufere',
                'phone' => '08160772622',
                'email' => 'drwallace@asknello.com',
                'password' => Hash::make(Str::random(8)),
                'picture' => 'https://res.cloudinary.com/dq1zd0mue/image/upload/v1577575183/doctor2_lilgnf.png',
                //'ufield' => '',
                'aos' => 'Orthopaedic Surgeon',
                'gender' => 'Male',
                'user_type' => 'doctor',
                'vendor_id' => 1
            ],
            [
                'uuid'      => Str::uuid()->toString(),
                'firstname' => 'Adeyemi',
                'middlename' => '',
                'lastname' => 'Bero',
                'phone' => '08033204507',
                'email' => 'dradeyemi@asknello.com',
                'password' => Hash::make(Str::random(8)),
                'picture' => 'https://res.cloudinary.com/dq1zd0mue/image/upload/v1577575183/doctor2_lilgnf.png',
                //'ufield' => '',
                'aos' => 'Obstetrician/Gynaecologist',
                'gender' => '',
                'user_type' => 'doctor',
                'vendor_id' => 1
            ],
            [
                'uuid'      => Str::uuid()->toString(),
                'firstname' => 'Victor',
                'middlename' => '',
                'lastname' => 'Dacosta',
                'phone' => '08023221206',
                'email' => 'drvictor@asknello.com',
                'password' => Hash::make(Str::random(8)),
                'picture' => 'https://res.cloudinary.com/dq1zd0mue/image/upload/v1577575183/doctor2_lilgnf.png',
                //'ufield' => '',
                'aos' => 'Dentist',
                'gender' => 'Male',
                'user_type' => 'doctor',
                'vendor_id' => 1
            ],
            [
                'uuid'      => Str::uuid()->toString(),
                'firstname' => 'Ima',
                'middlename' => '',
                'lastname' => 'Ima',
                'phone' => '08098485403',
                'email' => 'drima@asknello.com',
                'password' => Hash::make(Str::random(8)),
                'picture' => 'https://res.cloudinary.com/dq1zd0mue/image/upload/v1577575183/doctor2_lilgnf.png',
                //'ufield' => '',
                'aos' => 'Rheumatologist',
                'gender' => '',
                'user_type' => 'doctor',
                'vendor_id' => 1
            ],
            [
                'uuid'      => Str::uuid()->toString(),
                'firstname' => 'Nwife',
                'middlename' => '',
                'lastname' => 'Eneka',
                'phone' => '08023048133',
                'email' => 'drnwife@asknello.com',
                'password' => Hash::make(Str::random(8)),
                'picture' => 'https://res.cloudinary.com/dq1zd0mue/image/upload/v1577575183/doctor2_lilgnf.png',
                //'ufield' => '',
                'aos' => 'Psychiatrist',
                'gender' => '',
                'user_type' => 'doctor',
                'vendor_id' => 1
            ],
            [
                'uuid'      => Str::uuid()->toString(),
                'firstname' => 'Amosu',
                'middlename' => '',
                'lastname' => 'Amosu',
                'phone' => '08023039800',
                'email' => 'dramosu@asknello.com',
                'password' => Hash::make(Str::random(8)),
                'picture' => 'https://res.cloudinary.com/dq1zd0mue/image/upload/v1577575183/doctor2_lilgnf.png',
                //'ufield' => '',
                'aos' => 'Ear, Nose and Throat Specialist',
                'gender' => '',
                'user_type' => 'doctor',
                'vendor_id' => 1
            ],
            [
                'uuid'      => Str::uuid()->toString(),
                'firstname' => 'Johnson',
                'middlename' => '',
                'lastname' => 'Ogah',
                'phone' => '08069012451',
                'email' => 'drjohnson@asknello.com',
                'password' => Hash::make(Str::random(8)),
                'picture' => 'https://res.cloudinary.com/dq1zd0mue/image/upload/v1577575183/doctor2_lilgnf.png',
                //'ufield' => '',
                'aos' => 'Anesthesiologist',
                'gender' => 'Male',
                'user_type' => 'doctor',
                'vendor_id' => 1
            ],
            [
                'uuid'      => Str::uuid()->toString(),
                'firstname' => 'Austin',
                'middlename' => '',
                'lastname' => 'Okogun',
                'phone' => '07053351700',
                'email' => 'draustin@asknello.com',
                'password' => Hash::make(Str::random(8)),
                'picture' => 'https://res.cloudinary.com/dq1zd0mue/image/upload/v1577575183/doctor2_lilgnf.png',
                //'ufield' => '',
                'aos' => 'Occupational Health Specialist',
                'gender' => 'Male',
                'user_type' => 'doctor',
                'vendor_id' => 1
            ],
            [
                'uuid'      => Str::uuid()->toString(),
                'firstname' => 'Oresanya',
                'middlename' => '',
                'lastname' => 'Oresanya',
                'phone' => '08038210654',
                'email' => 'doresanya@asknello.com',
                'password' => Hash::make(Str::random(8)),
                'picture' => 'https://res.cloudinary.com/dq1zd0mue/image/upload/v1577575183/doctor2_lilgnf.png',
                //'ufield' => '',
                'aos' => 'Dermatologist',
                'gender' => '',
                'user_type' => 'doctor',
                'vendor_id' => 1
            ],
            [
                'uuid'      => Str::uuid()->toString(),
                'firstname' => 'Sadare',
                'middlename' => '',
                'lastname' => 'Sadare',
                'phone' => '08023070856',
                'email' => 'drsadare@asknello.com',
                'password' => Hash::make(Str::random(8)),
                'picture' => 'https://res.cloudinary.com/dq1zd0mue/image/upload/v1577575183/doctor2_lilgnf.png',
                //'ufield' => '',
                'aos' => 'Dentist',
                'gender' => '',
                'user_type' => 'doctor',
                'vendor_id' => 1
            ],
            [
                'uuid'      => Str::uuid()->toString(),
                'firstname' => 'Amy',
                'middlename' => '',
                'lastname' => 'Traore-Shumbusho',
                'phone' => '08033771233',
                'email' => 'dramy@asknello.com',
                'password' => Hash::make(Str::random(8)),
                'picture' => 'https://res.cloudinary.com/dq1zd0mue/image/upload/v1577575183/doctor2_lilgnf.png',
                //'ufield' => '',
                'aos' => 'Orthodontist',
                'gender' => '',
                'user_type' => 'doctor',
                'vendor_id' => 1
            ],
            [
                'uuid'      => Str::uuid()->toString(),
                'firstname' => 'Oluranti',
                'middlename' => '',
                'lastname' => 'Dacosta',
                'phone' => '08023221208',
                'email' => 'droluranti@asknello.com',
                'password' => Hash::make(Str::random(8)),
                'picture' => 'https://res.cloudinary.com/dq1zd0mue/image/upload/v1577575183/doctor2_lilgnf.png',
                //'ufield' => '',
                'aos' => 'Orthodontist',
                'gender' => '',
                'user_type' => 'doctor',
                'vendor_id' => 1
            ],
            [
                'uuid'      => Str::uuid()->toString(),
                'firstname' => 'Majebi',
                'middlename' => '',
                'lastname' => 'Majebi',
                'phone' => '08033265972',
                'email' => 'drmajebi@asknello.com',
                'password' => Hash::make(Str::random(8)),
                'picture' => 'https://res.cloudinary.com/dq1zd0mue/image/upload/v1577575183/doctor2_lilgnf.png',
                //'ufield' => '',
                'aos' => 'Psychiatrist',
                'gender' => '',
                'user_type' => 'doctor',
                'vendor_id' => 1
            ],
        ];

        //echo count($data);

        foreach($data as $info) {
            User::create($info);
        }
    }
}
