<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {

    }


    public function check(Request $request) 
    {
        $user = User::where('health_id', $request->health_id)->first();
        if ($user) {
            $user->append('package');
        }
        return $user;
    }

}
