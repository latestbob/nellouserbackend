<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\MyDemoMail;
use Mail;
use App\Models\Cart;
use App\Models\PharmacyDrug;
class FakeController extends Controller
{
    //

    public function send(){

        Mail::to('bobson@yahoo.com')->send(new MyDemoMail());

        return 'Working';
    }


   
}
