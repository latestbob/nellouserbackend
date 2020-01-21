<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PharmacyDrug;
use Illuminate\Http\Request;

class DrugController extends Controller
{
    public function index(Request $request)
    {
        if (empty($request->q)) {

            $drugs = PharmacyDrug::orderBy('name')->paginate();
        } else {

            $drugs = PharmacyDrug::where('name', 'like', '%' . $request->q . '%')
                ->orWhere('brand', 'like', '%' . $request->q . '%')
                ->orWhere('category', 'like', '%' . $request->q . '%')
                ->orderBy('name')->paginate();
        }
        return $drugs;
    }
}
