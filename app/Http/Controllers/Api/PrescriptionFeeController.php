<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Pharmacy;
use App\Models\PrescriptionFee;
use Illuminate\Http\Request;

class PrescriptionFeeController extends Controller
{
    public function getPrescriptionFee(Request $request)
    {
        return PrescriptionFee::where('fee', '>', 0)->select('fee')->first() ?: ['fee' => 0];
    }
}
