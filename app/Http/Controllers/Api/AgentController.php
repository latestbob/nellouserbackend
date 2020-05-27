<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function total(Request $request)
    {
        $pharmacy = $request->user()->pharmacy;

        if (($pharmacy->location_id ?? 0) == 0) {
            return [
                'status' => false,
                'message' => "Your pharmacy has not been assigned a location"
            ];
        }

        $orders = Order::where([['orders.location_id', '=', $pharmacy->location_id],
            ['is_ready', '=', true], ['is_ready_by', '=', $pharmacy->id]])
            ->join('carts', 'orders.cart_uuid', '=',
                'carts.cart_uuid', 'inner');

        return [
            'status' => true,
            'message' => 'Analysis retrieved successfully',
            'earnings' => $orders->sum('amount'),
            'processed' => $orders->count()
        ];
    }
}
