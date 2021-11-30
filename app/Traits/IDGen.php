<?php

namespace App\Traits;

use App\Models\User;

trait IDGen {


    public function generateHealthId() : string
    {
        do {
            $id = random_int(10000000, 99999999);
            $id = "{$id}";
        } while(User::where('health_id', $id)->exists());
        return $id;
    }

}