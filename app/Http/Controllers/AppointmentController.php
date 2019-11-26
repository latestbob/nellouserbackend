<?php

namespace App\Http\Controllers;

use Auth;
use App\Jobs\BookAppointment;
use App\Models\Appointment;
use App\Models\HealthCenter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

    public function bookAppointment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'medical_center' => 'required|string|exists:health_centers:uuid',
            'reason'         => 'required|string',
            'date'           => 'required|date',
            'time'           => 'required|date_format:H:i'
        ]);

        if ($validator->fails()) {
            return response($validator->errors(), 400);
        }

        $data = $validator->validated();
        $data['status'] = 'pending';
        $data['user_uuid'] = $request->user()->uuid;
        $data['center_uuid'] = $request->medical_center;
        $appointment = Appointment::create($data);
        return $appointment;
    }


    public function viewAppointment(Request $request)
    {
        $appointment = $this->find($request->uuid);
        return $appointment;
    }

    public function updateAppointment(Request $request)
    {
        $request->user_uuid = $request->user()->uuid;
        $validator = Validator::make($request->all(), [
            'uuid'           => 'required|exists:appointments',
            'medical_center' => 'required|string|exists:health_centers:uuid',
            'reason'         => 'required|string',
            'date'           => 'required|date',
            'time'           => 'required|date_format:H:i'
        ]);

        if ($validator->fails()) {
            return response($validator->errors(), 400);
        }

        $appointment = $this->find($request->uuid);
        if (empty($appointment)) return;

        $data = $validator->validated();
        $data['user_uuid'] = $request->user()->uuid;
        $data['center_uuid'] = $request->medical_center;
        $appointment->update($data);
        return $appointment;
    }

    public function cancelAppointment(Request $request)
    {
        $appointment = $this->find($request->uuid);
        if (empty($appointment)) return;
        $appointment->status = 'cancelled';
        $appointment->save();
        return $appointment;
    }
}
