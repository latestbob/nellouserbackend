<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\HealthCenter;
use App\Notifications\AppointmentBookedNotification;
use App\Notifications\AppointmentCancelledNotification;
use App\Notifications\AppointmentUpdatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Traits\GuzzleClient;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    use GuzzleClient;

    public function index(Request $request)
    {
        $user = $request->user();
        $appointments = Appointment::whereHas('user', function($query) use ($user) {
            $query->where('vendor_id', $user->vendor_id);
        })->paginate();
        return $appointments;
    }

    private function find(string $uuid): Appointment
    {
        $appointment = Appointment::where('uuid', $uuid)
            ->where('user_uuid', Auth::user()->uuid)
            ->first();
        return $appointment;
    }


    /**
     * Book appointment
     *
     * @bodyParam medical_center uuid required a health center uuid
     * @bodyParam reason string required the purpose of the appointment
     * @bodyParam date date format yyyy-mm-dd
     * @bodyParam time time format HH:mm
     */
    public function bookAppointment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'medical_center' => 'required|string',// |exists:health_centers,uuid',
            'reason'         => 'required|string',
            'date'           => 'required|date|after:today',
            'time'           => 'required|date_format:H:i'
        ]);

        if ($validator->fails()) {
            return response($validator->errors(), 400);
        }

        $user = $request->user();
        $data = $validator->validated();
        $data['uuid'] = Str::uuid()->toString();
        $data['status'] = 'pending';
        $data['user_uuid'] = $user->uuid;
        $data['center_uuid'] = $request->medical_center;
        $appointment = Appointment::create($data);
        $user->notify(new AppointmentBookedNotification($appointment));
        return $appointment;

        /**DO NOT DELETE */
        $user->load('vendor');

        try {

            $response = $this->httpPost($user->vendor, '/api/appointments/book', $data);

            if ($response->getReasonPhrase() === 'OK') {
                return $response->getBody();
            }
            return $response->getBody();
        } catch (RequestException $e) {
            echo Psr7\str($e->getRequest());
            if ($e->hasResponse()) {
                echo Psr7\str($e->getResponse());
            } else {
                print_r($e);
                //$str = json_encode($e, true);
            }
            return response([
                'msg' => 'Error while booking appointment.'
            ], 400);

        } catch (ClientException $e) {
            echo Psr7\str($e->getRequest());
            return response([
                'msg' => 'Error while booking appointment.'
            ], 400);
        }

        return response([
            'msg' => 'Error while booking appointment.'
        ], 400);
    }


    public function pending(Request $request)
    {
        $appointment = Appointment::with(['center'])->where([
            'user_uuid' => $request->user()->uuid,
            'status' => 'pending'
        ])->orderBy('created_at','desc')->first();

        return $appointment;

        /**DO NOT DELETE */
        $user = $request->user();
        $user->load('vendor');

        try {

            $response = $this->httpGet($user->vendor, '/api/appointments/pending', ['user_uuid' => $user->uuid]);

            //if ($response->getReasonPhrase() === 'OK') {
            //    return $response->getBody();
            //}
            return $response->getBody();
        } catch (RequestException $e) {
            echo Psr7\str($e->getRequest());
            if ($e->hasResponse()) {
                echo Psr7\str($e->getResponse());
            } else {
                print_r($e);
                //$str = json_encode($e, true);
            }
            return response([
                'msg' => 'Error while fetching pending appointment.'
            ], 400);

        } catch (ClientException $e) {
            echo Psr7\str($e->getRequest());
            return response([
                'msg' => 'Error while fetching pending appointment.'
            ], 400);
        }

        return response([
            'msg' => 'Error while fetching pending appointment.'
        ], 400);
    }

    /**
     * Update appointment
     *
     * Update the details of an appointment
     *
     * @bodyParam uuid uuid required the uuid of the appointment
     * @bodyParam medical_center uuid required a health center uuid
     * @bodyParam reason string required the purpose of the appointment
     * @bodyParam date date format yyyy-mm-dd
     * @bodyParam time time format HH:mm
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid'           => 'required|string',
            'reason'         => 'required|string',// |exists:health_centers,uuid',
            'date'           => 'required|date|after:today',
            'time'           => 'required|date_format:H:i'
        ]);

        if ($validator->fails()) {
            return response($validator->errors(), 400);
        }

        $user = $request->user();
        $appointment = Appointment::where('uuid', $request->uuid)->first();
        $data = $validator->validated();
        $appointment->update($data);
        $user->notify(new AppointmentUpdatedNotification($appointment));
        return $appointment;

        /**DO NOT DELETE */
        $user->load('vendor');

        try {

            $response = $this->httpPost($user->vendor, '/api/appointments/update', $data);

            //if ($response->getReasonPhrase() === 'OK') {
            //    return $response->getBody();
            //}

            //return $response->getBody();
            return ['message' => 'Appointment updated successfully'];
        } catch (RequestException $e) {
            echo Psr7\str($e->getRequest());
            if ($e->hasResponse()) {
                echo Psr7\str($e->getResponse());
            } else {
                print_r($e);
                //$str = json_encode($e, true);
            }
            return response([
                'msg' => 'Error while updating appointment.'
            ], 400);

        } catch (ClientException $e) {
            echo Psr7\str($e->getRequest());
            return response([
                'msg' => 'Error while updating appointment.'
            ], 400);
        }

        return response([
            'msg' => 'Error while updating appointment.'
        ], 400);
    }

    /**
     * Cancel appointment
     *
     * @urlParam uuid uuid required the uuid of the appointment
     */
    public function cancel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid'           => 'required|string',
        ]);

        if ($validator->fails()) {
            return response($validator->errors(), 400);
        }

        $user = $request->user();
        $appointment = Appointment::where('uuid', $request->uuid)->first();
        $appointment->status = 'cancelled';
        $appointment->save();
        $user->notify(new AppointmentCancelledNotification($appointment));
        return $appointment;

        /**DO NOT DELETE */
        $user->load('vendor');
        $data = $validator->validated();

        try {

            $response = $this->httpPost($user->vendor, '/api/appointments/cancel', $data);

            //if ($response->getReasonPhrase() === 'OK') {
            //    return $response->getBody();
            //}
            return $response->getBody();
        } catch (RequestException $e) {
            echo Psr7\str($e->getRequest());
            if ($e->hasResponse()) {
                echo Psr7\str($e->getResponse());
            } else {
                print_r($e);
                //$str = json_encode($e, true);
            }
            return response([
                'msg' => 'Error while updating appointment.'
            ], 400);

        } catch (ClientException $e) {
            echo Psr7\str($e->getRequest());
            return response([
                'msg' => 'Error while updating appointment.'
            ], 400);
        }

        return response([
            'msg' => 'Error while updating appointment.'
        ], 400);
    }


    /**
     * View Appointment
     *
     * View details of an appointment
     *
     * @urlParam uuid uuid required the uuid of the appointment
     */
    public function viewAppointment(Request $request)
    {
        if (empty($request->uuid)) {
            return response(['error' => 'Query parameter uuid is missing'], 400);
        }
        $appointment = $this->find($request->uuid);
        return $appointment;
    }
}
