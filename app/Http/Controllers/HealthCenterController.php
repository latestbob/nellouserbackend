<?php

namespace App\Http\Controllers;

use App\Models\HealthCenter;
use Illuminate\Http\Request;

class HealthCenterController extends Controller
{
    public function index(Request $request) {
        $centers = HealthCenter::orderBy('name')->paginate();
        return $centers;
    }
}
