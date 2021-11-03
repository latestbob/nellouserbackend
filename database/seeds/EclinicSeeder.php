<?php

use App\Jobs\ImportEclinicPatientJob;
use Illuminate\Database\Seeder;

class EclinicSeeder extends Seeder
{
   
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ImportEclinicPatientJob::dispatchNow();
    }

}
