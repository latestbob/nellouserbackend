<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UserHealthIdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::whereNull('health_id')
            ->get();
        
        foreach($users as $user) {
            $user->health_id = $this->generateHealthId();
            $user->save();
        }
    }

    private function generateHealthId() : string
    {
        do {
            $id = random_int(10000000, 99999999);
            $id = "{$id}";
        } while(User::where('health_id', $id)->exists());
        return $id;
    }

}
