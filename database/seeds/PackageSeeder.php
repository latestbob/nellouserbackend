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
                'price' => 20000,
                'description' => 'Recommended for individuals',
            ],
            [
                'uuid' => Str::uuid()->toString(),
                'name' => 'Family Plan',
                'price' => 100000,
                'description' => 'Recommended for families',
            ],
            [
                'uuid' => Str::uuid()->toString(),
                'name' => 'Basic Enterprise Plan',
                'price' => 200000,
                'description' => 'Supersized plan for the special ones',
            ],
            [
                'uuid' => Str::uuid()->toString(),
                'name' => 'Premium Enterprise Plan',
                'price' => 1100000,
                'description' => 'Supersized plan for the special ones',
            ],
            [
                'uuid' => Str::uuid()->toString(),
                'name' => 'Executive Enterprise Plan',
                'price' => 1700000,
                'description' => 'Supersized plan for the special ones',
            ],

        ];

        foreach($plans as $plan) {
            Package::create($plan);
        }
    }
}
