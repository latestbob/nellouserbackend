<?php

use App\Models\Benefit;
use Illuminate\Database\Seeder;

class BenefitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $benefits = [
            ['name' => 'Number of accounts'],
            ['name' => 'Ppersonalized health tips'],
            ['name' => 'Book appointments with healthcare specialists'],
            ['name' => 'Get prescriptions'],
            ['name' => 'Doctor consultation (chat and mobile)'],
            ['name' => 'Annual general checkup'],
            ['name' => 'Annual general health talk']
        ];

        foreach($benefits as $benefit) {
            Benefit::create($benefit);
        }

    }
}
