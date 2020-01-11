<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
        $conditions = ['user_type' => 'doctor'];
        if ($request->has('specialization')) {
            $conditions['specialization'] = $request->specialization;
        }
        $doctors = User::with(['vendor'])->where($conditions)->paginate();
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

    public function importDoctor(Request $request)
    {
        
    }

}
