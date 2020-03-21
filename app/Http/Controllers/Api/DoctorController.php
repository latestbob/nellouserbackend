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
    public function fetchDoctors(Request $request)
    {
        if (empty($request->specialization)) {

            $doctors = User::orderBy('firstname')->paginate();
        } else {

            $doctors = User::where('firstname', 'like', '%' . $request->specialization . '%')
                ->orWhere('lastname', 'like', '%' . $request->specialization . '%')
                ->orWhere('aos', 'like', '%' . $request->specialization . '%')
                ->orderBy('firstname')->paginate();
        }

        $doctors->load('vendor');
        return $doctors;
    }


    /**
     * Doctors specializations
     * 
     * Fetch list of doctors specializations
     */
    public function fetchSpecializations()
    {
        $specs = User::select('aos')->distinct()->get();
        return $specs;
    }

    /**
     * Rate a doctor
     * 
     * 
     * 
     * @bodyParam rating int required
     * @bodyParam doctor_uuid string required
     */
    public function rateDoctor(Request $request)
    {
        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'doctor_uuid' => 'required|string'
        ]);
        $data['user_uuid'] = $request->user()->uuid;

        $rating = DoctorRating::create($data);
        return $rating;
    }

    public function importDoctor(Request $request)
    {
        
    }

}
