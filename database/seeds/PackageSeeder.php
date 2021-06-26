<?php

use App\Models\Package;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $plans = [
            [
                'uuid' => Str::uuid()->toString(),
                'name' => 'Single Plan',
                'price' => 3000,
                'description' => 'Recommended for individuals',
            ],
            [
                'uuid' => Str::uuid()->toString(),
                'name' => 'Family Plan',
                'price' => 4000,
                'description' => 'Recommended for families',
            ],
            [
                'uuid' => Str::uuid()->toString(),
                'name' => 'Enterprise Plan',
                'price' => 8000,
                'description' => 'Supersized plan for the special ones',
            ],
        ];

        foreach($plans as $plan) {
            Package::create($plan);
        }
    }
}
