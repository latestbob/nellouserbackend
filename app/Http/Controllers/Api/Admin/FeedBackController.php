<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Feedback;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class FeedBackController extends Controller
{

    public function __construct()
    {
        Config::set('auth.providers', ['users' => [
            'driver' => 'eloquent',
            'model' => Admin::class,
        ]]);
    }

    public function getFeedbacks(Request $request) {

        try {
            $admin = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof TokenInvalidException){
                return response()->json(['status' => 'Token is Invalid'], 401);
            }else if ($e instanceof TokenExpiredException){
                return response()->json(['status' => 'Token is Expired'], 401);
            }else{
                return response()->json(['status' => 'Authorization Token not found'], 401);
            }
        }

        $size = empty($request->size) ? 1 : $request->size;
        $feedbacks = Feedback::where(['vendor_id' => $admin->vendor_id])
            ->orderBy('created_at', 'desc')->paginate($size);
        return $feedbacks;
    }

    public function getFeedbackAnalysis(Request $request) {

        try {
            $admin = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof TokenInvalidException){
                return response()->json(['status' => 'Token is Invalid'], 401);
            }else if ($e instanceof TokenExpiredException){
                return response()->json(['status' => 'Token is Expired'], 401);
            }else{
                return response()->json(['status' => 'Authorization Token not found'], 401);
            }
        }

        return [
            'happy' => Feedback::where(['vendor_id' => $admin->vendor_id, 'experience' => 'happy'])->count('id'),
            'sad' => Feedback::where(['vendor_id' => $admin->vendor_id, 'experience' => 'sad'])->count('id'),
            'neutral' => Feedback::where(['vendor_id' => $admin->vendor_id, 'experience' => 'neutral'])->count('id')
        ];
    }
}
