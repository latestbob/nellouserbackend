<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Http\Controllers\GuzzleClient;
use App\Jobs\RegisterCustomer;
use App\Models\Vendor;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    use GuzzleClient;

    public function getToken(Request $request)
    {
        $vendor = Vendor::where('api_key', $request->api_key)->first();
        return $this->getVendorToken($vendor);
    }

    /**
     * Customer login
     * 
     * @bodyParam email string required
     * @bodyParam password string required
     */
    public function loginCustomer(Request $request)
    {
        $user = User::With(['vendor'])->where('email', $request->email)->first();
        if (!$user) {
            return response([
                'msg' => 'Invalid Credentials.'
            ], 400);
        }

        $credentials = $request->only('email', 'password');


        try {

            $response = $this->httpPost($user->vendor, '/api/auth/login', $credentials);

            if ($response->getReasonPhrase() === 'OK') { }
            if (!$token = JWTAuth::fromUser($user)) {
                return response([
                    'msg' => 'Invalid Credentials.'
                ], 400);
            }

            //$user = Auth::user();

            return [
                'token'  => $token,
                'user'   => $user
            ];
            return $response->getBody();
        } catch (RequestException $e) {
            echo Psr7\str($e->getRequest());
            if ($e->hasResponse()) {
                return response(Psr7\str($e->getResponse()), 400);
            } else {
                print_r($e);
                $str = json_encode($e, true);
                return response($str, 400);
            }
        }

        return response([
            'msg' => 'Invalid Credentials.'
        ], 400);
    }


    /**
     * Customer registration
     * 
     * @bodyParam firstname string required
     * @bodyParam lastname string requird
     * @bodyParam email string required
     * @bodyParam phone string required
     * @bodyParam password string required
     * @bodyParam dob date optional format yyyy-mm-dd
     */
    public function registerCustomer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:50',
            'lastname'  => 'required|string|max:50',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|numeric|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'dob' => 'nullable|date'
        ]);


        if ($validator->fails()) {
            return response($validator->errors(), 400);
        }

        $vendor = Vendor::find(1);

        $userData = $validator->validated();
        $userData['vendor_id'] = 1;
        $userData['user_type'] = 'customer';

        try {

            $response = $this->httpPost($vendor, '/api/auth/register', $userData);

            if ($response->getReasonPhrase() === 'OK') {
                return $response->getBody();
            }
            return $response->getBody();
        } catch (RequestException $e) {
            echo Psr7\str($e->getRequest());
            if ($e->hasResponse()) {
                return response(Psr7\str($e->getResponse()), 400);
            } else {
                print_r($e);
                $str = json_encode($e, true);
                return response($str, 400);
            }
        }

        return response([
            'msg' => 'Error while creating account.'
        ], 400);

        //$user = User::create($userData);
        //RegisterCustomer::dispatch($user);
        //return $user;
    }

    public function getUser(Request $request)
    {
        return ['user' => $request->user()];
    }
}
