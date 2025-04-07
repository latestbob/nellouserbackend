<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Mail\OrderDelivered;

use Mail;

class EmailController extends Controller
{
    //Delivered Order Mail

    public function EmailController(){

       return "working";

    }


    public function confirmme(Request $request){
            $validator = Validator::make($request->all(), [
            'deliver' => 'required',
            'email' => 'required',
            
        ]);

        if ($validator->fails()) {
            return response([
                'status' => false,
                'message' => $validator->errors()
            ]);
        }


         




         Mail::to($request->email)->send(new OrderDelivered($request->deliver));


        //return "working";

    }
}
