<?php

use App\Jobs\ForgotPasswordJob;
use App\Models\User;
use Illuminate\Database\Seeder;

class ResetPasswordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        foreach($users as $user) {
            ForgotPasswordJob::dispatch($user); //->onConnection('database')->onQueue('mails');
        }
    }
}
