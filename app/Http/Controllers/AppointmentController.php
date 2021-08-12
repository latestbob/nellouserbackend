<?php

namespace App\Http\Controllers;

use App\Jobs\SendAppointmentEmail;
use App\Models\Appointment;
use App\Models\HealthCenter;
use App\Notifications\AppointmentBookedNotification;
use App\Notifications\AppointmentCancelledNotification;
use App\Notifications\AppointmentUpdatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Traits\GuzzleClient;
use Carbon\Carbon;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AppointmentController extends Controller
{
    use GuzzleClient;

    public function index(Request $request)
    {
        $user = $request->user();
        $appointments = Appointment::whereHas('user', function ($query) use ($user) {
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
        $input = $request->all();

        if (isset($input['time'])) $input['time'] = date('Y-m-d H:i', strtotime("{$input['date']} {$input['time']}"));

        $data = $request->validate([
            'reason' => 'required|string',
            'description' => 'nullable|string',
            'type' => 'nullable|string',
            'date' => 'required|date|after_or_equal:today',
            //'time' => 'required|date_format:Y-m-d H:i|after:' . date('Y-m-d H:i', strtotime("+30 minutes")),
            'time' => [
                'required',
                'date_format:H:i:s',
                function ($attr, $value, $fail) use($request) {
                    $frags1 = explode('-', $request->date);
                    $frags = explode(':', $value);
                    $now = Carbon::now()->addMinutes(30);
                    $time = Carbon::now();
                    $time->setDate($frags1[0], $frags1[1], $frags1[2])
                        ->setHour($frags[0])
                        ->setMinute($frags[1])
                        ->setSecond($frags[2]);

                    if ($now->gte($time)) {
                        $fail("Time must be at least 30 minutes after the current time.");
                    }
                }
            ],
            'medical_center' => [
                Rule::requiredIf(empty($request->doctor_id)),
                'exists:health_centers,uuid'
            ],
            'doctor_id' => [
                Rule::requiredIf(empty($request->medical_center)),
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('user_type', 'doctor');
                })
            ]
        ]);

        $user = $request->user();

        //if (isset($data['time'])) $data['time'] = \DateTime::createFromFormat(
        //    "Y-m-d H:i", $data['time'])->format("H:i");

        $data['uuid'] = Str::uuid()->toString();
        $data['status'] = 'pending';
        $data['user_uuid'] = $user->uuid;
        if ($request->medical_center) {
            $data['center_uuid'] = $request->medical_center;
        }

        $check = Appointment::with(['doctor'])->where([
            'doctor_id' => $request->doctor_id,
            'date' => $data['date'], 'time' => $data['time'], ['status', '!=', 'cancelled']
        ])->first();

        /*$check = Appointment::with(['center'])->where(['center_uuid' => $request->medical_center,
            'date' => $data['date'], 'time' => $data['time'], ['status', '!=', 'cancelled']])->first();
        */

        if (!empty($check)) {
            return response([
                'status' => false,
                'message' => [
                    ["Sorry, an appointment has already been booked at Dr. {$check->doctor->firstname} with your selected time. Select another time and try again."]
                ]
            ]);
        }

        $appointment = Appointment::create($data);
        $user->notify(new AppointmentBookedNotification($appointment));
        SendAppointmentEmail::dispatch($appointment);
        return response([
            'status' => true,
            'message' => "Appointment booked successfully",
            'appointment' => $appointment
        ]);

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
            ]);
        } catch (ClientException $e) {
            echo Psr7\str($e->getRequest());
            return response([
                'msg' => 'Error while booking appointment.'
            ]);
        }

        return response([
            'msg' => 'Error while booking appointment.'
        ]);
    }


    public function pending(Request $request)
    {
        $appointment = Appointment::with(['center'])->where([
            'user_uuid' => $request->user()->uuid,
            'status' => 'pending'
        ])->orderBy('created_at', 'desc')->first();

        return [
            'appointment' => $appointment
        ];

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
            ]);
        } catch (ClientException $e) {
            echo Psr7\str($e->getRequest());
            return response([
                'msg' => 'Error while fetching pending appointment.'
            ]);
        }

        return response([
            'msg' => 'Error while fetching pending appointment.'
        ]);
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
        $input = $request->all();

        if (isset($input['time'])) $input['time'] = date('Y-m-d H:i', strtotime("{$input['date']} {$input['time']}"));

        $validator = Validator::make($input, [
            'uuid' => 'required|string',
            'reason' => 'required|string',
            'type' => 'required|string',
            'description' => 'required|string',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:Y-m-d H:i|after:' . date('Y-m-d H:i', strtotime("+30 minutes"))
        ]);

        if ($validator->fails()) {
            return response([
                'status' => false,
                'message' => $validator->errors()
            ]);
        }

        $user = $request->user();
        $appointment = Appointment::with(['center'])->where('uuid', $request->uuid)->first();
        $data = $validator->validated();

        if (isset($data['time'])) $data['time'] = \DateTime::createFromFormat(
            "Y-m-d H:i",
            $data['time']
        )->format("H:i");

        $check = Appointment::with(['center'])->where([
            'center_uuid' => $appointment->center_uuid,
            'date' => $data['date'], 'time' => $data['time'], ['id', '!=', $appointment->id],
            ['status', '!=', 'cancelled']
        ])->first();

        if (!empty($check)) {
            return response([
                'status' => false,
                'message' => [
                    ["Sorry, an appointment has already been booked at {$check->center->name} with your selected time. Select another time and try again."]
                ]
            ]);
        }

        $appointment->update($data);

        $user->notify(new AppointmentUpdatedNotification($appointment));

        return [
            'status' => true,
            'message' => 'Appointment updated successfully',
            'appointment' => $appointment
        ];

        /**DO NOT DELETE */
        $user->load('vendor');

        try {

            $response = $this->httpPost($user->vendor, '/api/appointments/update', $data);

            //if ($response->getReasonPhrase() === 'OK') {
            //    return $response->getBody();
            //}

            //return $response->getBody();
            return response([
                'status' => true,
                'message' => 'Appointment updated successfully'
            ]);
        } catch (RequestException $e) {
            echo Psr7\str($e->getRequest());
            if ($e->hasResponse()) {
                echo Psr7\str($e->getResponse());
            } else {
                print_r($e);
                //$str = json_encode($e, true);
            }
            return response([
                'status' => false,
                'message' => 'Error while updating appointment.'
            ]);
        } catch (ClientException $e) {
            echo Psr7\str($e->getRequest());
            return response([
                'status' => false,
                'message' => 'Error while updating appointment.'
            ]);
        }
    }

    /**
     * Cancel appointment
     *
     * @urlParam uuid uuid required the uuid of the appointment
     */
    public function cancel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response([
                'status' => false,
                'message' => $validator->errors()
            ]);
        }

        $user = $request->user();
        $appointment = Appointment::with(['center'])->where('uuid', $request->uuid)->first();
        $appointment->status = 'cancelled';
        $appointment->save();
        $user->notify(new AppointmentCancelledNotification($appointment));

        return response([
            'status' => true,
            'message' => 'Appointment cancelled successfully',
            'current' => $appointment,
            'appointment' => $this->pending($request)['appointment']
        ]);

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
            ]);
        } catch (ClientException $e) {
            echo Psr7\str($e->getRequest());
            return response([
                'msg' => 'Error while updating appointment.'
            ]);
        }

        return response([
            'msg' => 'Error while updating appointment.'
        ]);
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
            return response(['error' => 'Query parameter uuid is missing']);
        }
        $appointment = $this->find($request->uuid);
        return $appointment;
    }
}
