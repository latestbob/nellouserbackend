<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PharmacyDrug;
use Illuminate\Http\Request;

class DrugController extends Controller
{
    public function index(Request $request)
    {
        if (empty($request->search) && empty($request->category)) {

            $drugs = PharmacyDrug::where('status', true)->orderBy('name')->paginate();

        } else {

            $search = $request->search;
            $category = $request->category;

            $drugs = PharmacyDrug::where('status', true)
                ->when($category, function ($query, $category) {
                    $query->where('category', '=', "{$category}");
                })->when($search, function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('brand', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%");
                })->orderBy('name')->paginate();
        }

        return $drugs;
    }

    public function getDrug(Request $request)
    {
        return PharmacyDrug::where([['uuid', '=', $request->uuid], ['status', '=', true]])->first();
    }

    public function getDrugCategories(Request $request)
    {
        return PharmacyDrug::where([['category', '!=', ""], ['status', '=', true]])
            ->select('category')->groupBy('category')->get();
    }
}
