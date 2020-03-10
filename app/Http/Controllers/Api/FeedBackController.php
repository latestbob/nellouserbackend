<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Feedbacks;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
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

    public function create(Request $request) {

        $validator = Validator::make($request->all(), [
            'phone' => 'nullable|string',
            'feedback'  => 'required|string',
            'experience'  => 'required|string',
            'facilityID' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }

        $data = $validator->validated();
        $data['vendor_id'] = $data['facilityID'];
        unset($data['facilityID']);

        $feedback = Feedbacks::create($data);
        return [
            'message' => 'Feedback sent successfully',
            'data' => [$feedback]
        ];
    }

    public function getFeedbacks(Request $request) {

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

        if (empty($request->facilityID)) {
            return response(['msg' => ['Facility ID is missing']], 400);
        }

        $size = empty($request->size) ? 1 : $request->size;
        $feedbacks = Feedbacks::where(['vendor_id' => $request->facilityID])
            ->orderBy('created_at', 'desc')->paginate($size);
        return $feedbacks;
    }
}
