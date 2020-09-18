<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DoctorRating;
use App\Models\User;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    /**
     * Doctors
     *
     * Fetch paged list of doctors
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
     */
    public function fetchDoctors(Request $request)
    {
        $doctors = User::with(['vendor'])->where('user_type', 'doctor')
            ->when($request->search, function($query, $search){
                $query->whereRaw('(firstname LIKE ? or middlename LIKE ? or lastname LIKE ?)',
                    ["%{$search}%", "%{$search}%", "%{$search}%"]);
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
        $specs = User::whereNotNull('aos')->select('aos')->distinct()->get();
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

        $rating = DoctorRating::create($data);
        return $rating;
    }

    public function importDoctor(Request $request)
    {

    }

}
