<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;


class FamacareController extends Controller
{
    //

    public function index(){
        




$response = Http::withoutVerifying()->withHeaders([
    'Content-Type' => 'application/json',
    'Accept' => 'application/json',
    
])->get('https://api2.famacare.eclathealthcare.com/patient?page=143');


$final = $response["_embedded"]["Patient"];

// foreach($final as $finals){
//     return  $finals;
// }

foreach($final as $key => $userss)
{
	//dump($value);

    $user = new User;

        $user->upi = $userss["upi"];
    $user->firstname = $userss["forename"];
    $user->lastname = $userss["surname"];
    $user->email = $userss["email"];
    $user->dob = Carbon::parse($userss["dob"])->toDateString();

    // Carbon::parse($userss["dob"])->toDateString();

    $user->gender = $userss["gender"];
    $user->phone = $userss["phone"];

    $user->password = Hash::make($userss["upi"]);
    $user->active = 1;
    $user->user_type = "customer";

    $user->vendor_id = 1;
   
    $user->uuid = Str::uuid()->toString();
    //$userData['health_id'] = $this->generateHealthId();

    $user->save();
}

return "Working";
//return $response["Patient"];
// $eclatusers = $response["_embedded"]["Patient"];




// foreach($eclatusers as $userss){
//     //dump($userss);

    

//     $user->upi = $userss["upi"];
//     $user->firstname = $userss["forename"];
//     $user->lastname = $userss["surname"];
//     $user->email = $userss["email"];
//     $user->dob = $userss["dob"];

//     $user->gender = $userss["gender"];
//     $user->phone = $userss["phone"];

//     $user->password = Hash::make($userss["upi"]);
//     $user->active = 1;
//     $user->user_type = "customer";

//     $user->vendor_id = 1;
   
//     $user->uuid = Str::uuid()->toString();
//     //$userData['health_id'] = $this->generateHealthId();

//     $user->save();

//     dump("Saved");

   



    


}

    }


