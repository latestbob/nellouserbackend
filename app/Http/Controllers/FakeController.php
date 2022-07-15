<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\MyDemoMail;
use Mail;

class FakeController extends Controller
{
    //

    public function send(){

        Mail::to('bobson@yahoo.com')->send(new MyDemoMail());

        return 'Working';
    }
}
