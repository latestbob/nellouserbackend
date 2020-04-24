<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Locations;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function getLocations(Request $request)
    {
        return [
            'locations' => Locations::all(['id', 'name', 'price'])
        ];
    }
}
