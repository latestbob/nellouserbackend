<?php

use Illuminate\Database\Seeder;

class VendorAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vendors = \App\Models\Vendor::all();
        foreach ($vendors as  $vendor) {

            $faker = \Faker\Factory::create();

            $admin = new \App\Models\Admin();
            $admin->email = $faker->email;
            $admin->phone = $faker->e164PhoneNumber;
            $admin->password = \Illuminate\Support\Facades\Hash::make("password");
            $admin->vendor_id = $vendor->id;
            $admin->save();
        }
    }
}
