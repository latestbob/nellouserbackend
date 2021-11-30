<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\VerificationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Traits\IDGen;


class UserController extends Controller
{
    use IDGen;

    public function index()
    {

    }


    public function check(Request $request) 
    {
        $user = User::where('health_id', $request->health_id)->first();

        if ($user) {
            $user->append('package');
            return $user;
        }

        return response(['error' => 'User not found'], 404);
    }

    public function create(Request $request)
    {
        $data = $request->validate([
            'firstname' => 'required|string|max:50',
            'lastname' => 'required|string|max:50',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'required|digits_between:11,16|unique:users,phone',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'same:password',
            'gender' => 'required|string|in:Male,Female',
            'weight' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'dob' => 'required|date_format:d-m-Y|before_or_equal:today'
        ]);


        $data['vendor_id'] = 1;
        $data['user_type'] = 'customer';
        $data['uuid'] = Str::uuid()->toString();
        $data['health_id'] = $this->generateHealthId();
        $data['password'] = Hash::make($data['password']);

        $data['token'] = Str::random(15);
        $user = User::create($data);

        $user->notify(new VerificationNotification());

        return $user;
    }

}
