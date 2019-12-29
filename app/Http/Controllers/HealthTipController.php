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
        $today = Carbon::today();
        //$tips = HealthTip::where(['day' => $today->day, 'month' => $today->month, 'year' => $today->year])->first();
        $tips = HealthTip::all();
        return $tips;
    }

    public function lastTip() {
        $tip = HealthTip::orderBy('created_at', 'desc')->first();
        return $tip;
    }
}
