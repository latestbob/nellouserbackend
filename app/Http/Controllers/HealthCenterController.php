<?php

namespace App\Http\Controllers;

use App\Models\HealthCenter;
use Illuminate\Http\Request;

class HealthCenterController extends Controller
{
    /**
     * Health centers
     * 
     * Fetch paged list of health centers
     * 
     * @urlParam page int optional defaults to 1
     */
    public function index(Request $request) {
        $centers = HealthCenter::orderBy('name')->get(); //->paginate();
        return $centers;
    }
}
