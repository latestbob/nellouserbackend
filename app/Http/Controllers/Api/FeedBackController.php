<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class FeedBackController extends Controller
{

    public function create(Request $request) {

        $validator = Validator::make($request->all(), [
            'phone' => 'required|digits_between:11,16',
            'feedback'  => 'nullable|string',
            'experience'  => 'required|string',
            'facilityID' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()]);
        }

        $data = $validator->validated();
        $data['vendor_id'] = $data['facilityID'];
        unset($data['facilityID']);

        $feedback = Feedback::create($data);
        return [
            'message' => 'Feedback sent successfully',
            'data' => [$feedback]
        ];
    }
}
