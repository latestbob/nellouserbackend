<?php

use App\Models\Vendor;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vendor = new Vendor;
        $vendor->name = 'Famacare Limited';
        $vendor->server_url = 'http://206.189.236.121:2000';
        $vendor->api_key = 'Gb78@566Nbshah)6%@3b';
        $vendor->api_secret = '26acxZaatUU1vX5K0Jb9rgVr4xv3AZnEDc9jJ6UyjdsgguSqtmvYNWGFPHGlabI6';
        $vendor->save();
    }
}
