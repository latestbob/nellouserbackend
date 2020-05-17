<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HealthTip;
use Carbon\Carbon;

class HealthTipController extends Controller
{

    /**
     * Health tips
     *
     */
    public function index()
    {
//        $today = Carbon::today();
        //$tips = HealthTip::where(['day' => $today->day, 'month' => $today->month, 'year' => $today->year])->first();
//        $tips = HealthTip::all();
        return HealthTip::orderByDesc('id')->paginate();
    }

    public function todayTip() {
        return HealthTip::whereMonth('date', Carbon::today()->month)->orderByDesc('id')->first();
    }

    public function viewTip(Request $request) {
        return HealthTip::where('uuid', $request->uuid)->first();
    }
}
