<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;
use App\Customerfeedback;
use Illuminate\Support\Facades\Validator;
use App\Mail\UserFeeback;
use App\Mail\AdminFeedback;
use Mail;

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


    ///customer feedback

    public function customerfeedback(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email'  => 'required',
            'type'  => 'required',
            'message' => 'required'
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()]);
        }

        $feedback = new Customerfeedback;

        //type:selectedSla.name,
        // priority:selectedSla.priority,
        // resolution_time : selectedSla.resolution_time,
        // dependencies: selectedSla.dependencies,


        // $table->string("priority");
        // $table->integer("resolution_time");
        // $table->string("dependencies");

        $feedback->name = $request->name;
        $feedback->email = $request->email;
        $feedback->type = $request->type;
        $feedback->message = $request->message;
        $feedback->priority = $request->priority;
        $feedback->resolution_time = $request->resolution_time;
        $feedback->dependencies = $request->dependencies;
        $feedback->save();


        $feedback = [
            "name" => $request->name
        ];

        $adminfeedback = [
            "name"=> $request->name,
            "email" => $request->email,
            "type" => $request->type,
            "message" => $request->message
        ];

        Mail::to($request->email)->send(new UserFeeback($feedback));

        Mail::to("support@asknello.com")->send(new AdminFeedback($adminfeedback));


        return response()->json([
            "status" => "success",
            "email" => $request->email,
            "name" => $request->name,
            "message" => "Feedback has been sent, our customer support will get in touch with you",
        ]);

}


// delete all feedback


public function deletefeedback(Request $request){
    $feedback = Customerfeedback:: truncate();

    return "done";
}

}