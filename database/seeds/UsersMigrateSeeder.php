<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersMigrateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = DB::connection('mysql_old')->table('users')->get();

        $data = [];
        foreach($users as $user) {
            $data[] = [

            ];
        }

        User::create($data);
    }
}
