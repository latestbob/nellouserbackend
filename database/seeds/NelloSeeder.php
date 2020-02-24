<?php

use App\Jobs\ImportNelloPatientsJob;
use Illuminate\Database\Seeder;

class NelloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ImportNelloPatientsJob::dispatchNow();
    }
}
