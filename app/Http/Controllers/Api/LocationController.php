<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Locations;
use App\Models\Pharmacies;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function getLocations(Request $request)
    {
        return [
            'locations' => [
                'shipping' => Locations::join('pharmacies', 'locations.id', '=', 'pharmacies.location_id', 'inner')->havingRaw(
                    "count(pharmacies.id) > ?", [0]
                )->groupBy('locations.name')->get(['locations.id', 'locations.name', 'price']),
                'pickup' => Pharmacies::where('is_pick_up_location', true)->select(['id', 'name', 'address'])->get()
            ]
        ];
    }
}
