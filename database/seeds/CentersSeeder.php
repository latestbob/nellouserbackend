<?php

use Illuminate\Support\Str;
use App\Models\HealthCenter;
use Illuminate\Database\Seeder;

class CentersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $centers = [
            [
                'logo' => 'https://res.cloudinary.com/dq1zd0mue/image/upload/v1577645225/Rectangle44_ncbdsd.png',
                'vendor_id' => 1,
                'name' => 'Farmacare Center Limited',
                'address1' => '108 Akawanji Rd. Alimasha, Lagos',
                'center_type'  => 'clinic',
                'phone' => '2348123456789',
                'email' => 'info@famacare.com',
                'state' => 'Lagos',
                'city' => 'Lagos',
                'is_active' => true
            ],
            [
                'logo' => 'https://res.cloudinary.com/dq1zd0mue/image/upload/v1577645225/Rectangle45_q9w45f.png',
                'vendor_id' => 1,
                'name' => 'Reddington Hospital',
                'address1' => '12 Idowu Martins Street, Victoria Island, Lagos',
                'center_type'  => 'hospital',
                'phone' => '012715341',
                'email' => 'info@reddingtonhospital.com',
                'state' => 'Lagos',
                'city' => 'Lagos',
                'is_active' => true
            ],
            [
                'logo' => 'https://res.cloudinary.com/dq1zd0mue/image/upload/v1577645226/Rectangle46_tp0qjn.png',
                'vendor_id' => 1,
                'name' => 'Renaissance Medical Center',
                'address1' => '8B Elsie, Femi-Pearse, Victoria Island, Lagos',
                'center_type'  => 'hospital',
                'phone' => '09030769633',
                'email' => 'info@renaissancehospital.com',
                'state' => 'Lagos',
                'city' => 'Lagos',
                'is_active' => true
            ],            
        ];

        foreach($centers as $center) {
            $center['uuid'] = Str::uuid()->toString(); 
            HealthCenter::create($center);
        }
    }
}
