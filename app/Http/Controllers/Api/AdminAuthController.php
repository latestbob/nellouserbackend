<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\AdminForgotPasswordJob;
use App\Jobs\AdminResetPasswordJob;
use App\Models\Admin;
use App\Models\PasswordReset;
use App\Traits\GuzzleClient;
use Exception;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminAuthController extends Controller
{

    use GuzzleClient;

    public function __construct()
    {
        Config::set('auth.providers', ['users' => [
            'driver' => 'eloquent',
            'model' => Admin::class,
        ]]);
    }

    public function loginAdmin(Request $request)
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

        try {

            $admin = Admin::where('email', $request->email)->first();

            if ($admin) {

                if (Hash::check($request->password, $admin->password)) {

                    $admin->load('vendor');

                    return [
                        'token'  => JWTAuth::fromUser($admin),
                        'user'   => $admin
                    ];
                }
            }
            return response([
                'msg' => 'Invalid Credentials.',
                'type' => 'Empty user'
            ], 400);

        } catch (RequestException $e) {
            return response([
                'msg' => 'Invalid Credentials.',
            ], 400);
        }
    }

    public function forgotPasswordAdmin(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|exists:users,email'
        ]);

        if ($validator->fails()) {
            return response([
                'msg' => $validator->errors()
            ], 400);
        }

        $admin = Admin::where('email', $request->email)->with('vendor')->first();

        AdminForgotPasswordJob::dispatch($admin);

        return [
            'email' => $admin->email,
            'msg' => "A password reset code has been sent to your mail box at {$admin->email}",
        ];
    }

    public function resetPasswordAdmin(Request $request)
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

        $admin = Admin::where('email', $request->email)->with('vendor')->first();

        try {

            AdminResetPasswordJob::dispatch(
                $admin,
                $request->password
            );

            $pass->delete();

            return [
                'msg' => "Your password has been reset successfully",
            ];

            //return $response->getBody();
        } catch (RequestException $e) {

            if ($e->hasResponse()) {
                return response(Psr7\str($e->getResponse()), 400);
            } else {
                //print_r($e);
                $str = json_encode($e, true);
                return response($str, 400);
            }
        }

    }

    public function getUser(Request $request)
    {
        try {
            $admin = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof TokenInvalidException){
                return response()->json(['status' => 'Token is Invalid']);
            }else if ($e instanceof TokenExpiredException){
                return response()->json(['status' => 'Token is Expired']);
            }else{
                return response()->json(['status' => 'Authorization Token not found']);
            }
        }

        if ($admin) $admin->load('vendor');
        return ['user' => $admin];
    }
}
