<?php

use App\Models\User;
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
                'firstname' => 'Doctor',
                'lastname' => 'One',
                'phone' => '08938238292',
                'email' => 'doctor@one.com',
                'password' => Hash::make('password'),
                'picture' => 'https://res.cloudinary.com/dq1zd0mue/image/upload/v1577575183/doctor2_lilgnf.png',
                //'ufield' => '',
                'aos' => 'Dentistry',
                'gender' => 'Male',
                'user_type' => 'doctor',
                'vendor_id' => 1
            ],
            [
                'firstname' => 'Doctor',
                'lastname' => 'Two',
                'phone' => '08938231192',
                'email' => 'doctor@two.com',
                'password' => Hash::make('password'),
                'picture' => 'https://res.cloudinary.com/dq1zd0mue/image/upload/v1577575183/doctor7_doqgme.png',
                //'ufield' => '',
                'aos' => 'Cardiology',
                'gender' => 'Female',
                'user_type' => 'doctor',
                'vendor_id' => 1
            ]
        ];
        foreach($data as $info) {
            User::create($info);
        }
    }
}
