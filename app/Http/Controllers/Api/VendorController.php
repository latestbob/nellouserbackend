<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    //
    public function getAllVendors(Request $request) {
        return [
            'facilities' => Vendor::all(['id', 'name'])
        ];
    }
}
