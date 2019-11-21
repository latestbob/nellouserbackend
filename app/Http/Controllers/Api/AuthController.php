<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function loginCustomer(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response([
                //'status' => 'error',
                //'error' => 'invalid.credentials',
                'msg' => ['Invalid Credentials.']
            ], 400);
        }

        $user = Auth::user();

        $data = [
            'token'  => $token,
            'user'   => $user
        ];

        if ($user->user_type == 'collector') {
            $collector = Collector::where('user_id', $user->id)->first();
            $collector->smsBalance = $collector->getSmsBalance();
            $data['collector'] = $collector;  
        } else if ($user->user_type == 'contributor') {
            $contributor = Contributor::where('user_id', $user->id)->first();
            $contributor->totalDeposit = $contributor->getTotalDeposit();
            $contributor->totalWithdrawal = $contributor->getTotalWithdrawal();
            $data['contributor'] = $contributor;
        }

        return $data;
    } 
}
