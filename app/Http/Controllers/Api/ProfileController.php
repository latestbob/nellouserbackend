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
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    use GuzzleClient;



    /**
     * Update customer profile
     * 
     * @bodyParam firstname string required
     * @bodyParam lastname string required
     * @bodyParam middlename string
     * @bodyParam email string required
     * @bodyParam phone string required
     * @bodyParam dob date optional format yyyy-mm-dd
     * @bodyParam address string
     * @bodyParam state string
     * @bodyParam city string
     * @bodyParam religion string
     * @bodyParam gender string optional male or female
     * @bodyParam height numeric
     * @bodyParam weight numeric
     */
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
            'height' => 'numeric',
            'weight' => 'numeric',
            'sponsor' => 'string'
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


    /**
     * Upload picture
     * 
     * Upload customer profile picture
     * 
     * @bodyParam picture file required image file
     */
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
            //UploadPicture::dispatch([
            //    'uuid' => $user->uuid,
            //    'vendor_id' => $user->vendor_id,
            //    'picture' => $imageUrl
            //]);
            return ['image_url' => $imageUrl];
        }
        
        return response(['error' => 'Image not found'], 400);
    }

    /**
     * Health history
     * 
     * Fetch customer's health history data
     */
    public function fetchHealthHistory(Request $request)
    {
        $data = User::with([
            'encounters',
            'investigations',
            'medications',
            'payments',
            //'invoices',
            'procedures'
        ])->find($request->user()->id);
        return $data;
    }

    public function fetchMedicalReports(Request $request)
    { }


    public function reorderDrugs(Request $request)
    { }

    /**
     * Change password
     * 
     * Change customer password
     * 
     * @bodyParam current_password string required
     * @bodyParam new_password string required
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password'     => 'required|string'
        ]);

        $user = Auth::user();
        $vendor = Vendor::find($user->vendor_id);

        $userData = [
            'current_password' => $request->current_password,
            'password' => $request->new_password,
            'uuid'     => $user->uuid
        ];

        try {

            $response = $this->httpPost($vendor, '/api/password/change', $userData);

            if ($response->getReasonPhrase() === 'OK') {
                return $response->getBody();
            }
            return $response->getBody();
        } catch (RequestException $e) {
            echo Psr7\str($e->getRequest());
            if ($e->hasResponse()) {
                return response(Psr7\str($e->getResponse()), 400);
            } else {
                print_r($e);
                $str = json_encode($e, true);
                return response($str, 400);
            }
        }
    }
}
