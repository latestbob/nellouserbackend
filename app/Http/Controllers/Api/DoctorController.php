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
     */
    public function fetchDoctors(Request $request)
    {
        $doctors = User::with(['vendor'])->where('user_type', 'doctor')->paginate();
        return $doctors;
    }

    public function importDoctor(Request $request)
    {
        
    }

}
