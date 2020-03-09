<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FeedBacks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FeedBackController extends Controller
{
    //

    public function create(Request $request) {

        $validator = Validator::make($request->all(), [
            'phone' => 'nullable|string',
            'feedback'  => 'required|string',
            'facilityID' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response(['msg' => $validator->errors()], 400);
        }

        $data = $validator->validated();
        $data['vendor_id'] = $data['facilityID'];
        unset($data['facilityID']);

        $feedback = FeedBacks::create($data);
        return ['data' => [$feedback] ];
    }

    public function getFeedbacks(Request $request) {

        return ['feedbacks' => FeedBacks::where(['vendor_id', '=', $request->facilityID])->get()];
    }
}
