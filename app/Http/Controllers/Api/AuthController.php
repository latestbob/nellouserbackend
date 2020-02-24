<?php

namespace App\Http\Controllers\Api;

use App\Jobs\ForgotPasswordJob;
use App\Jobs\ResetPasswordJob;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Traits\GuzzleClient;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;

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
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8'
        ]);


        if ($validator->fails()) {
            return response([
                'msg' => 'Invalid Credentials.',
                'type' => 'Validation error.',
                'errors' => $validator->errors()
            ], 400);
        }

        $credentials = $request->only(['email', 'password']);

        $vendor = Vendor::find(1);
        try {
            $response = $this->httpPost($vendor, '/api/auth/login', $credentials);

            if ($response->getReasonPhrase() === 'OK') {
            }

            $user = User::where('email', $request->email)->first();
            if ($user) {
                $token = JWTAuth::fromUser($user);
                return [
                    'token'  => $token,
                    'user'   => $user
                ];
            }
            return response([
                'msg' => 'Invalid Credentials.',
                'type' => 'Empty user'
            ], 400);

            //            return $response->getBody();
        } catch (RequestException $e) {
            //echo Psr7\str($e->getRequest());
            //if ($e->hasResponse()) {
                //return response(Psr7\str($e->getResponse()), 400);
            //} else {
                //print_r($e);
                //$str = json_encode($e, true);
                //return response($str, 400);
            //}
            return response([
                'msg' => 'Invalid Credentials.',
            ], 400);
        } catch (ClientException $e) {
            //echo Psr7\str($e->getRequest());
            return response([
                'msg' => 'Invalid Credentials.'
            ], 400);
        }
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
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'required|numeric|unique:users,phone',
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
        $userData['uuid'] = Str::uuid()->toString();

        if (!empty($userData['dob'])) {
            $userData['dob'] = Carbon::parse($userData['dob'])->toDateString();
        }

        $user = User::create($userData);

        try {

            $response = $this->httpPost($vendor, '/api/auth/register', $userData);

            if ($response->getReasonPhrase() === 'OK') {
                return $response->getBody();
            }
            return $response->getBody();
        } catch (RequestException $e) {
            //echo Psr7\str($e->getRequest());
            if ($e->hasResponse()) {
                return response(Psr7\str($e->getResponse()), 400);
            } else {
                //print_r($e);
                $str = json_encode($e, true);
                return response($str, 400);
            }
        } catch (ClientException $e) {
            echo Psr7\str($e->getRequest());
            return response([
                'msg' => 'Invalid Credentials.'
            ], 400);
        }

        return response([
            'msg' => 'Error while creating account.'
        ], 400);
    }

    public function forgotPasswordCustomer(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|exists:users,email'
        ]);

        if ($validator->fails()) {
            return response([
                'msg' => $validator->errors()
            ], 400);
        }

        $user = User::where('email', '=', $request->email)->first();

        ForgotPasswordJob::dispatch($user); //->onConnection('database')->onQueue('mails');

        return [
            'email' => $user->email,
            'msg' => "A password reset code has been sent to your mail box at {$user->email}",
        ];
    }

    public function resetPasswordCustomer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|exists:users,email',
            'code' => 'required|string|max:255|exists:password_resets,token',
            'password' => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            return response([
                'msg' => $validator->errors()
            ], 400);
        }

        $pass = PasswordReset::where('token', '=', $request->code)->first();

        if ($pass->email != $request->email) {
            return response([
                'msg' => [
                    'code' => ['That code was not generated for the specified account']
                ]
            ], 400);
        }

        if (time() > (strtotime($pass->created_at) + (60 * 60))) {
            return response([
                'msg' => [
                    'code' => ['Sorry that code has expired']
                ]
            ], 400);
        }

        $user = User::where('email', $request->email)->first();
        $vendor = Vendor::find($user->vendor_id);

        $userData = [
            //'current_password' => $request->current_password,
            'password' => $request->password,
            'uuid'     => $user->uuid
        ];

        try {

            $response = $this->httpPost($vendor, '/api/password/change', $userData);

            //if ($response->getReasonPhrase() === 'OK') {
                //return $response->getBody();
            //}

            ResetPasswordJob::dispatch(
                $user,
                $request->password
            ); //->onConnection('database')->onQueue('mails');
    
            $pass->delete();
    
            return [
                'msg' => "Your password has been reset successfully",
            ];
                
            //return $response->getBody();
        } catch (RequestException $e) {
            /*return response([
                'msg' => [
                    'code' => ['Sorry an error occured. Please try again.']
                ]
            ], 400);*/

            //echo Psr7\str($e->getRequest());
            if ($e->hasResponse()) {
                return response(Psr7\str($e->getResponse()), 400);
            } else {
                //print_r($e);
                $str = json_encode($e, true);
                return response($str, 400);
            }
        }



        //$user = User::where(['email' => $request->email])->first();

        //$user->update([
        //    'password' => Hash::make($request->password)
        //]);
    }

    public function getUser(Request $request)
    {
        $token = $request->bearerToken();
        $user = JWTAuth::toUser($token);
        return ['user' => $user];
    }

    public function nelloCreateUser(Request $request)
    {
        $data = $request->all();
        $user = User::create($data);
        return $user;
    }
}
