<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
// use Illuminate\Validation\Rule;

class EmbanqoController extends Controller
{
    //

    public function validatephone(Request $request){
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|exists:users,phone'
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'failed',
                'message' => $validator->errors()
            ]);
        }

        $user = User::where('phone',$request->phone)->first();

        return response()->json([
            'status' => 'success',
            'data'=> [
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'phone' => $user->phone,
                'email' => $user->email,
                'dob' => $user->dob,
                'gender' => $user->gender
            ]
            
        ]);

    }
}
