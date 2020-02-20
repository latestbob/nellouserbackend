<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Appointment;
use App\Models\HealthCenter;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Traits\GuzzleClient;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;

class AppointmentController extends Controller
{
    use GuzzleClient;

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
            'medical_center' => 'required|string|exists:health_centers,uuid',
            'reason'         => 'required|string',
            'date'           => 'required|date|after:today',
            'time'           => 'required|date_format:H:i'
        ]);

        if ($validator->fails()) {
            return response($validator->errors(), 400);
        }

        $data = $validator->validated();
        $data['uuid'] = Str::uuid()->toString();
        $data['status'] = 'pending';
        $data['user_uuid'] = $request->user()->uuid;
        //$data['date'] = $request->date;
        //$data['time'] = $request->time;
        $data['center_uuid'] = $request->medical_center;
        //$appointment = Appointment::create($data);
        //return $appointment;
        $user = $request->user();
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

        if (empty($appointment)) return [];

        $appointment->date = $appointment->app_date;
        $appointment->time = $appointment->app_time;

        return $appointment;
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
    public function updateAppointment(Request $request)
    {
        $request->user_uuid = $request->user()->uuid;
        $validator = Validator::make($request->all(), [
            'uuid'           => 'required|exists:appointments,uuid',
//            'medical_center' => 'required|string', //|exists:health_centers:uuid',
            'reason'         => 'required|string',
            'date'           => 'required|date|after:today',
            'time'           => 'required|date_format:H:i'
        ]);

        if ($validator->fails()) {
            return response($validator->errors(), 400);
        }

        $appointment = $this->find($request->uuid);
        if (empty($appointment)) return ['message' => 'Appointment update failed'];

        $data = $validator->validated();
        $data['app_date'] = $request->date;
        $data['app_time'] = $request->time;
//        $data['user_uuid'] = $request->user()->uuid;
//        $data['center_uuid'] = $request->medical_center;
        $appointment->update($data);
        return ['message' => 'Appointment updated successfully'];
    }


    /**
     * Cancel appointment
     * 
     * @urlParam uuid uuid required the uuid of the appointment
     */
    public function cancelAppointment(Request $request)
    {
        $appointment = $this->find($request->uuid);
        if (empty($appointment)) return [];
        $appointment->status = 'cancelled';
        $appointment->save();
        return $appointment;
    }

    public function pendingAppointment(Request $request)
    {
        $user = $request->user();
        $appointment = Appointment::with(['center'])->where([
            'user_uuid' => $user->uuid,
            'status' => 'pending'
            ])->orderBy('created_at','desc')->first();

        if (empty($appointment)) return [];

        $appointment->date = $appointment->app_date;
        $appointment->time = $appointment->app_time;
        return $appointment;
    }
}
