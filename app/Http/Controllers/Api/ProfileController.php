<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\GuzzleClient;
use App\Models\Vendor;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;

use App\Models\Appointment;
use App\Models\Encounter;
use App\Models\Medication;
use Illuminate\Support\Facades\Auth;
use JD\Cloudder\Facades\Cloudder;

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
            'middlename' => 'nullable|string',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|numeric',
            'dob' => 'nullable|date',
            'address' => 'nullable|string',
            'state' => 'nullable|string',
            'city'  => 'nullable|string',
            'religion' => 'nullable|string',
            'gender' => 'string|in:Male,Female',
            'height' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
            'sponsor' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response([
                'msg' => $validator->errors()
            ], 400);
        }

        $user = $request->user();
        $user->load('vendor');
        $data = $validator->validated();
        $user->update($data);

        return ['msg' => 'Profile updated successfully.', 'user' => $user];

        /**DO NOT DELETE */
        $data['uuid'] = $user->uuid;


        try {

            $response = $this->httpPost($user->vendor, '/api/profile/update', $data);

            if ($response->getReasonPhrase() === 'OK') {
                $user->update($data);
                return ['msg' => 'Profile updated successfully.', 'user' => $user];
            }

            return response([
                'msg' => 'Error while updating account.'
            ], 400);
            //return $response->getBody();
        } catch (RequestException $e) {
            echo Psr7\str($e->getRequest());
            if ($e->hasResponse()) {
                echo Psr7\str($e->getResponse());
            } else {
                print_r($e);
            }
            return response([
                'msg' => 'Error while updating account.'
            ], 400);
        } catch (ClientException $e) {
            echo Psr7\str($e->getRequest());
            return response([
                'msg' => 'Error while updating account.'
            ], 400);
        }

        return response([
            'msg' => 'Error while updating account.'
        ], 400);
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
            $user = $request->user();
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

        $user = $request->user();
        $vendor = Vendor::find($user->vendor_id);

        $user->password = bcrypt($request->new_password);
        $user->save();

        return ['msg' => 'Password changed'];

        /** DO NOT DELETE */
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


    public function inBrief(Request $request) {
        $user = $request->user();
        $appointment = Appointment::where('user_uuid', $user->uuid)->orderBy('created_at','desc')->first();
        $medication = Medication::where('user_uuid', $user->uuid)->orderBy('created_at', 'desc')->first();
        $encounter = Encounter::where('user_uuid', $user->uuid)->orderBy('created_at', 'desc')->first();

        return [
            'appointment' => $appointment,
            'medication' => $medication,
            'encounter' => $encounter
        ];
    }

    public function encounters(Request $request)
    {
        return $request->user()->encounters()->get();
        return $this->__request($request, '/api/profile/encounters');
    }

    public function medications(Request $request)
    {
        return $request->user()->medications()->get();
        return $this->__request($request, '/api/profile/medications');
    }

    public function vitalSigns(Request $request)
    {
        return $request->user()->vitals()->get();
        return $this->__request($request, '/api/profile/vital-signs');
    }

    public function procedures(Request $request)
    {
        return $request->user()->procedures()->get();
        return $this->__request($request, '/api/profile/procedures');
    }

    public function investigations(Request $request)
    {
        return $request->user()->investigations()->get();
        return $this->__request($request, '/api/profile/investigations');
    }

    public function invoices(Request $request)
    {
        return $this->__request($request, '/api/profile/invoices');
    }

    public function payments(Request $request)
    {
        return $request->user()->payments()->get();
        return $this->__request($request, '/api/profile/payments');
    }

    private function __request(Request $request, string $endpoint)
    {
        $user = $request->user();
        $user->load('vendor');

        try {

            $response = $this->httpGet($user->vendor, $endpoint, ['user_uuid' => $user->uuid]);

            if ($response->getReasonPhrase() === 'OK') {
                return $response->getBody();
            }
        } catch (RequestException $e) {
            echo Psr7\str($e->getRequest());
            if ($e->hasResponse()) {
                echo Psr7\str($e->getResponse());
            } else {
                print_r($e);
                //$str = json_encode($e, true);
            }
            return response([
                'msg' => 'An error occurred while fetching data.'
            ], 400);

        } catch (ClientException $e) {
            echo Psr7\str($e->getRequest());
            return response([
                'msg' => 'An error occurred while fetching data.'
            ], 400);
        }

        return response([
            'msg' => 'An error occurred while fetching data.'
        ], 400);
    }

    public function appointments(Request $request)
    {
        $appointments = Appointment::where('user_uuid', $request->user()->uuid)
            ->paginate();
        return $appointments;
    }
}
