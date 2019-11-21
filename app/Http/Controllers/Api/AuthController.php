<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\GuzzleClient;

class AuthController extends Controller
{

    use GuzzleClient;


    public function loginCustomer(Request $request)
    {
        $user = User::With(['vendor'])->where('email', $request->email)->first();
        if (!$user) {
            return response([
                'msg' => 'Invalid Credentials.'
            ], 400);
        }

        $credentials = $request->only('email', 'password');

        $response = $this->httpPost($user->vendor, '/api/auth/login', $credentials->toArray());

        if ($response->getReasonPhrase() === 'OK') {
            if (!$token = JWTAuth::attempt($request->only(['email']))) {
                return response([
                    'msg' => 'Invalid Credentials.'
                ], 400);
            }

            $user = Auth::user();

            return [
                'token'  => $token,
                'user'   => $user
            ];
        }

        return response([
            'msg' => 'Invalid Credentials.'
        ], 400);
    }
}
