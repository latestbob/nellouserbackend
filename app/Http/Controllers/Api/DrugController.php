<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PharmacyDrug;
use Illuminate\Http\Request;

class DrugController extends Controller
{
    public function index(Request $request)
    {
        if (empty($request->search)) {

            $drugs = PharmacyDrug::where('status', true)->orderBy('name')->paginate();

        } else {

            $drugs = PharmacyDrug::where('status', true)
                ->where('name', 'like', "%{$request->search}%")
                ->orWhere('brand', 'like', "%{$request->search}%")
                ->orWhere('category', 'like', "%{$request->search}%")
                ->orderBy('name')->paginate();
        }

        return $drugs;
    }

    public function getDrug(Request $request)
    {
        return PharmacyDrug::where('uuid', $request->uuid)->first();
    }
}
