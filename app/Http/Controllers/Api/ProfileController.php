<?php

namespace App\Http\Controllers\Api;


use Auth;
use Cloudder;
use App\Http\Controllers\Controller;
use App\Jobs\UpdateCustomer;
use App\Jobs\UploadPicture;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\GuzzleClient;
use App\Models\Vendor;

class ProfileController extends Controller
{
    use GuzzleClient;

    public function updateCustomer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:50',
            'lastname'  => 'required|string|max:50',
            'middlename' => 'string',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|numeric',
            'dob' => 'nullable|date',
            'address' => 'string',
            'state' => 'string',
            'city'  => 'string',
            'religion' => 'string',
            'gender' => 'string',
            'height' => 'string',
            'weight' => 'string',
        ]);


        if ($validator->fails()) {
            return response($validator->errors(), 400);
        }

        $userData = $validator->validated();
        //$user = Auth::user();
        //$user->update($userData);
        //$user->load('vendor');
        //$this->httpPost($user->vendor, '/api/profile/update', $userData);


        if (Auth::check()) {
            //from end user
            $userData['local_saved'] = false;
            $user = Auth::user();
        } else {
            //from vendor  
            $user = User::where('uuid', $request->uuid)->first();
        }

        $user->update($userData);
        //UpdateCustomer::dispatch($userData);
        return $user;
    }

    public function uploadPicture(Request $request)
    {

        if ($request->hasFile('picture')) {
            //from end user
            Cloudder::upload($request->file('picture'));
            $response = Cloudder::getResult();
            $imageUrl = $response['url'];
            $user = Auth::user();
            $user->picture = $imageUrl;
            $user->save();
            UploadPicture::dispatch([
                'uuid' => $user->uuid,
                'vendor_id' => $user->vendor_id,
                'picture' => $imageUrl
            ]);
            return ['image_url' => $imageUrl];
        }
        
        return response(['error' => 'Image not found'], 400);
    }

    public function fetchHealthHistory(Request $request)
    {
        $data = User::with([
            'encounters',
            'investigations',
            'medications',
            'payments',
            'invoices',
            'procedures'
        ])->find(Auth::user()->id);
        return $data;
    }

    public function fetchMedicalReports(Request $request)
    { }


    public function reorderDrugs(Request $request)
    { }
}
