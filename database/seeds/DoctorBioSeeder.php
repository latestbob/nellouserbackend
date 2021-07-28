<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class DoctorBioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::where('user_type', 'doctor')->update([
            'about' => "These examples of bios aren’t new ideas, but they show patients who the provider really is. And every hospital should be doing this — at bare minimum."
        ]);
    }
}
