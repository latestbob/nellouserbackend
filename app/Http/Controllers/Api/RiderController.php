<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\RiderTripLog;
use App\Traits\FirebaseNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RiderController extends Controller
{
    use FirebaseNotification;

    public function acceptOrder(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:orders,id'
        ]);

        if ($validator->fails()) {
            return response([
                'status' => false,
                'message' => $validator->errors()
            ], 422);
        }

        $order = Order::where('id', $request->id)->first();

        if ($order->location_id != ($user = $request->user())->location_id) {
            return response([
                'status' => false,
                'message' => "Sorry, you're not assigned to that order's location"
            ], 422);
        }

        if ($order->accepted_pick_up == true) {
            return response([
                'status' => false,
                'message' => "Sorry, a rider has already accepted to pick up this order"
            ], 422);
        }

        $order->update([
            'accepted_pick_up' => true,
            'accepted_pick_up_by' => $user->id
        ]);

        $riders = [];
        foreach ($order->location->riders as $rider) {
            if ($rider->id == $request->user()->id) continue;
            $riders[] = $rider->device_token;
        }

        if (!empty($riders)) $this->sendNotification($riders,
            "Order Accepted", "A rider has accepted the order", 'high', [
                'orderId' => $request->id
            ]);

        return [
            'status' => true,
            'message' => 'Pick up accepted successfully'
        ];
    }

    public function pickedUp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:orders,id'
        ]);

        if ($validator->fails()) {
            return response([
                'status' => false,
                'message' => $validator->errors()
            ]);
        }

        $order = Order::where('id', $request->id)->first();

        if ($order->location_id != ($user = $request->user())->location_id) {
            return [
                'status' => false,
                'message' => "Sorry, you're not assigned to that order's location"
            ];
        }

        if ($order->accepted_pick_up != true) {
            return response([
                'status' => false,
                'message' => "Sorry, you have to accept this order before picking it up"
            ], 422);
        }

        if ($order->is_picked_up == true) {
            return response([
                'status' => false,
                'message' => "Sorry, this order has already been picked up"
            ], 422);
        }

        if ($order->accepted_pick_up_by != $user->id) {
            return response([
                'status' => false,
                'message' => "Sorry, you can't pick up an order you did not accept"
            ], 422);
        }

        $order->update([
            'is_picked_up' => true,
            'picked_up_by' => $user->id
        ]);

        return [
            'status' => true,
            'message' => 'Successfully marked order as picked up'
        ];
    }

    public function delivered(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:orders,id',
            'distance' => 'required|numeric',
            'time' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response([
                'status' => false,
                'message' => $validator->errors()
            ], 422);
        }

        $order = Order::where('id', $request->id)->first();

        if ($order->location_id != ($user = $request->user())->location_id) {
            return response([
                'status' => false,
                'message' => "Sorry, you're not assigned to that order's location"
            ], 422);
        }

        if ($order->is_picked_up != true) {
            return response([
                'status' => false,
                'message' => "Sorry, you can't deliver an order that has not been picked up"
            ], 422);
        }

        if ($order->picked_up_by != $user->id) {
            return response([
                'status' => false,
                'message' => "Sorry, you can't deliver an order you did not pick up"
            ], 422);
        }

        if ($order->delivery_status == true) {
            return response([
                'status' => false,
                'message' => "Sorry, this order has already been delivered"
            ], 422);
        }

        RiderTripLog::create([
            'rider_id' => $user->id,
            'order_id' => $order->id,
            'distance' => $request->distance,
            'time' => $request->time
        ]);

        $order->update([
            'delivery_status' => true,
            'delivered_by' => $user->id
        ]);

        return [
            'status' => true,
            'message' => 'Successfully marked order as delivered'
        ];
    }

    public function deliveryHistory(Request $request)
    {
        return Order::with('items')
            ->where('delivered_by', $request->user()->id)
            ->paginate();
    }

    public function tripAnalysis(Request $request)
    {
        return [
            'delivery' => Order::where('delivered_by', $request->user()->id)->count(),
            'distance' => RiderTripLog::where('rider_id', $request->user()->id)->sum('distance'),
            'time' => RiderTripLog::where('rider_id', $request->user()->id)->sum('time')
        ];
    }
}
