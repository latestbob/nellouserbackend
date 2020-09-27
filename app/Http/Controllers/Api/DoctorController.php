<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DoctorContact;
use App\Models\DoctorRating;
use App\Models\User;
use App\Notifications\DoctorContactNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DoctorController extends Controller
{
    /**
     * Doctors
     *
     * Fetch specific doctor
     *
     * @urlParam page int optional defaults to 1
     * @urlParam specialization string optional
     */
    public function fetchDoctor(Request $request)
    {
        return User::with(['vendor'])->where(['user_type' => 'doctor', 'uuid' => $request->uuid])->first();
    }
    /**
     * Doctors
     *
     * Fetch paged list of doctors
     *
     * @urlParam page int optional defaults to 1
     * @urlParam specialization string optional
     * @urlParam search string optional
     */
    public function fetchDoctors(Request $request)
    {
        $doctors = User::with(['vendor'])->where(['user_type' => 'doctor', 'active' => true])
            ->when($request->search, function($query, $search){
                $query->whereRaw('(firstname LIKE ? or middlename LIKE ? or lastname LIKE ? or hospital like ?)',
                    ["%{$search}%", "%{$search}%", "%{$search}%", "%{$search}%"]);
            })->when($request->specialization, function($query, $spec){
                $query->where('aos', 'LIKE', "%{$spec}%");
            })->paginate();

        return $doctors;
    }


    /**
     * Doctors specializations
     *
     * Fetch list of doctors specializations
     */
    public function fetchSpecializations()
    {
        $specs = User::where('user_type', 'doctor')->whereNotNull('aos')->select('aos')->distinct()->get();
        return $specs;
    }

    /**
     * Rate a doctor
     *
     *
     * @bodyParam rating int required
     * @bodyParam doctor_uuid string required
     */
    public function rateDoctor(Request $request)
    {
        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'doctor_uuid' => 'required|uuid'
        ]);
        $data['user_uuid'] = $request->user()->uuid;

        return DoctorRating::create($data);
    }

    public function contactDoctor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required_without:user_id|string',
            'email'  => 'required_without:user_id|email',
            'subject'  => 'required|string|min:10',
            'message'  => 'required|string|min:30',
            'user_id' => 'nullable|integer|exists:users,id',
            'doctor_id' => 'required|integer'
        ], [
            'name.required_without' => 'The name field is required',
            'email.required_without' => 'The email field is required',
        ]);

        if ($validator->fails()) return [
            'status' => false,
            'message' => $validator->errors()
        ];

        $doctor = User::where(['user_type' => 'doctor', 'id' => $request->doctor_id])->first();

        if (empty($doctor)) return [
            'status' => false,
            'message' => "That doctor was not found on the nello platform"
        ];

        $data = $validator->validated();

        $contact = DoctorContact::create($data);

        if (empty($contact)) return [
            'status' => false,
            'message' => "Sorry, we couldn't contact that doctor at this time"
        ];

        $doctor->notify(new DoctorContactNotification($contact));

        return [
            'status' => true,
            'message' => 'Your message has been sent successfully'
        ];
    }

}
