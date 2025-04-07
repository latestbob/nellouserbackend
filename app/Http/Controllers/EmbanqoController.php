<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
// use Illuminate\Validation\Rule;

use App\Jobs\SendAppointmentEmail;
use App\Models\Appointment;



use App\Mail\AppointmentCustomer;
use App\Mail\AppointmentDoctor;

use Mail;
use App\Mail\AppointmentMedical;

class EmbanqoController extends Controller
{
    //

    public function validatephone(Request $request){
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|exists:users,phone'
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'failed',
                'message' => $validator->errors()
            ]);
        }

        $user = User::where('phone',$request->phone)->first();

        return response()->json([
            'status' => 'success',
            'data'=> [
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'phone' => $user->phone,
                'email' => $user->email,
                'dob' => $user->dob,
                'gender' => $user->gender
            ]
            
        ]);

    }

    //send mail confirmation

    public function sendconfirmation(Request $request){
        $validator = Validator::make($request->all(), [
            'usermail' => 'required',
            'doctormail' => 'required',
            'data' => 'required'
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'failed',
                'message' => $validator->errors()
            ]);
        }

        $customerdetails = $request->data;
        

        Mail::to($request->usermail)->send(new AppointmentCustomer($customerdetails));
       
   Mail::to($request->doctormail)->send(new AppointmentDoctor($customerdetails));

    Mail::to("support@asknello.com")->send(new AppointmentCustomer($customerdetails));

    return "Mail Sent";
    }


//facility mail 

public function facilitymail(Request $request){
    $validator = Validator::make($request->all(), [
        'centername' => 'required',
            'time' => 'required',
            'date' => 'required',
           
            "link"=> 'required',
            "useremail" => 'required'
    ]);

    if ($validator->fails()) {
        return response([
            'status' => 'failed',
            'message' => $validator->errors()
        ]);
    }

    $medical = [
        'centername' => $request->centername,
        'time' => $request->time,
        'date' => $request->date,
       
        "link"=> $request->link,
        
     
    ];

    Mail::to($request->useremail)->send(new AppointmentMedical($medical));
    Mail::to("support@asknello.com")->send(new AppointmentMedical($medical));


    return 'working';
}

}
