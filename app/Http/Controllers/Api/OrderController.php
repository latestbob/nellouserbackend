<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendOrderMail;
use App\Models\Cart;
use App\Models\CustomerPointRule;
use App\Models\CustomerPoint;
use App\Models\Location;
use App\Models\Order;
use App\Models\PrescriptionFee;
use App\Models\TransactionLog;
use App\Models\User;
use App\Models\PharmacyDrug;
use App\Notifications\VerificationNotification;
use App\Traits\FirebaseNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Traits\Paystack;
use App\Traits\CouponCode;

class OrderController extends Controller
{
    use FirebaseNotification, Paystack, CouponCode;


    public function checkout(Request $request)
    {

        // Validate Request and assign to data
        $data = $request->validate([
            'firstname' => 'nullable|string',
            'lastname' => 'nullable|string',
            'company' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|digits_between:11,16',
            'cart_uuid' => 'required|string|exists:carts,cart_uuid',
            'delivery_method' => 'required|string|in:shipping,pickup',
            'delivery_type' => 'required_if:delivery_method,shipping|string|in:standard,same_day,next_day',
            'shipping_address' => 'required_if:delivery_method,shipping|string',
            'location_id' => 'required_if:delivery_method,shipping|numeric|exists:locations,id',
            'pickup_location_id' => 'required_if:delivery_method,pickup|numeric|exists:pharmacies,id',
            //'city' => 'required_if:delivery_method,shipping|string',
            'payment_method' => 'required|string|in:card,point',
            'payment_reference' => 'required_if:payment_method,card|string',
            'coupon_code' => 'nullable|exists:coupons,code',
            'add_prescription_charge' => 'nullable|in:yes,no'
        ]);


        

        //get Login User Info
        
        $user = Auth::user();

        $data['customer_id'] = $user->id;
        $data['email'] = $request->email ?? $user->email;
        $data['phone'] = $request->phone ?? $user->phone;
        $data['firstname'] = $request->firstname ?? $user->firstname;
        $data['lastname'] = $request->lastname ?? $user->lastname;


        // Set Shipping Address
        if (isset($data['shipping_address'])) {
            $data['address1'] = $data['shipping_address'];
            unset($data['shipping_address']);
        }


        // Set Pickup Location ID
        if (empty($data['location_id'])) {
            $data['location_id'] = $data['pickup_location_id'];
        }

        $order = Order::where(['cart_uuid' => $request->cart_uuid])->first();

        // set Order Reference
        $data['order_ref'] = strtoupper(Str::random(10));


        // if Order Exists before
        if (!empty($order)) {

            if ($order->payment_confirmed == 1) {
                return response([
                    'status' => false,
                    'message' => [["You already checked out and made payment for those cart items"]]
                ], 400);
            } else {

                $cart = Cart::where('cart_uuid', $data['cart_uuid']);
                $data['amount'] = $cart->sum('price');

                if ($request->add_prescription_charge === 'yes') {

                    $data['amount'] = $data['amount'] + 1000;
                }

                if ($request->coupon_code) {
                    $discount = $this->computeValue($request->coupon_code, $data['amount']);
                    $data['amount'] = $data['amount'] - $discount;
                }

                if ($request->delivery_method == 'shipping') {
                    $location = Location::where('id', $data['location_id'] ?? 0)->first();
                    $delType = "{$request->delivery_type}_price";
                    $data['amount'] +=  $location->{$delType};
                }

                // foreach ($cart->get() as $item) {
                //     if ($item->drug->require_prescription == true && empty($item->prescription)) {
                //         $data['amount'] += (PrescriptionFee::where('fee', '>', 0)->select('fee')->first() ?: (object)['fee' => 0])->fee;
                //         break;
                //     }
                // }

                $data['amount'] = ceil($data['amount']);

                // Pay with points We don't need point now

                // if ($data['payment_method'] == 'point') {

                //     if (!isset($data['customer_id']))
                //         return response([
                //             'status' => false,
                //             'message' => [['You must be logged in to pay with points']]
                //         ], 400);

                //     $point = CustomerPoint::where('customer_id', $data['customer_id'])->first();

                //     if (empty($point))
                //         return response([
                //             'status' => false,
                //             'message' => [['You have not earned any points yet']]
                //         ], 400);

                //     $rules = CustomerPointRule::orderByDesc('id')->limit(1)->first();

                //     if (empty($rules))
                //         return response([
                //             'status' => false,
                //             'message' => [['Point rules have not been set yet, contact system administrator']]
                //         ], 400);

                //     $pointValue = ($rules['point_value'] * $point['point']);

                //     if ($pointValue < $data['amount'])
                //         return response([
                //             'status' => false,
                //             'message' => [["You don't have enough points to purchase the items in your cart"]]
                //         ], 400);

                //     $point->point = ceil((($pointValue - $data['amount']) / $rules['point_value']));
                //     $point->save();

                //     $data['payment_confirmed'] = 1;
                // }

                $order->update($data);

                //SendOrderMail::dispatch($order, SendOrderMail::ORDER_CONFIRMED);

                if (($data['payment_confirmed'] ?? 0) == 1) {

                   // SendOrderMail::dispatch($order, SendOrderMail::ORDER_PAYMENT_RECEIVED);
                }

                return [
                    'status' => true,
                    'message' => ($data['payment_confirmed'] ?? 0) == 1 ?
                        'Thank you. Your checkout was successful and payment has been made using your points' :
                        'Checkout successful',
                    'order' => $order,
                    'payment_confirmed' => ($data['payment_confirmed'] ?? 0)
                ];
            }
        }

        
        // where Order hasn't exists
        $cart = Cart::where('cart_uuid', $data['cart_uuid']);
        $data['amount'] = $cart->sum('price');


        // setting coupon_code if exists
        if ($request->coupon_code) {
            $discount = $this->computeValue($request->coupon_code, $data['amount']);
            $data['amount'] = $data['amount'] - $discount;
        }

            //set delivery methods
        if ($request->delivery_method == 'shipping') {
            $location = Location::where('id', $data['location_id'] ?? 0)->first();
            $delType = "{$request->delivery_type}_price";
            $data['amount'] +=  $location->{$delType};
        }


        foreach ($cart->get() as $item) {
            if ($item->drug->require_prescription == true && empty($item->prescription)) {
                $data['amount'] += (PrescriptionFee::where('fee', '>', 0)->select('fee')->first() ?: (object)['fee' => 0])->fee;
                break;
            }
        }

        $data['amount'] = ceil($data['amount']);

        $respMsg = "";

        // if ($data['payment_method'] == 'point') {

        //     if (!isset($data['customer_id']))
        //         return response(['status' => false, 'message' => [['You must be logged in to pay with points']]], 400);

        //     $point = CustomerPoint::where('customer_id', $data['customer_id'])->first();

        //     if (empty($point))
        //         return response(['status' => false, 'message' => [['You have not earned any points yet']]], 400);

        //     $rules = CustomerPointRule::orderByDesc('id')->limit(1)->first();

        //     if (empty($rules))
        //         return response([
        //             'status' => false,
        //             'message' => [['Point rules have not been set yet, contact system administrator']]
        //         ], 400);

        //     $pointValue = ($rules['point_value'] * $point['point']);

        //     if ($pointValue < $data['amount'])
        //         return response([
        //             'status' => false,
        //             'message' => [["You don't have enough points to purchase the items in your cart"]]
        //         ], 400);

        //     $point->point = ceil((($pointValue - $data['amount']) / $rules['point_value']));
        //     $point->save();

        //     $data['payment_confirmed'] = 1;
        //     $respMsg = 'Thank you. Your checkout was successful and payment has been made using your points.';
        // } 
        if ($data['payment_method'] == 'card') {
            $check = $this->verify($data['payment_reference'], $data['amount']);
            if ($check['status']) {
                TransactionLog::create([
                    'gateway_reference' => $data['payment_reference'],
                    'system_reference' => $data['order_ref'],
                    'reason' => 'Order payment',
                    'amount' => $data['amount'],
                    'email' => $data['email']
                ]);
                $data['payment_confirmed'] = 1;
                $respMsg = 'Thank you. Your checkout was successful and payment confirmed.';
            } else {
                return response([
                    'errors' => [
                        'payment_reference' => [$check['message']]
                    ]
                ], 422);
                $respMsg = "Checkout successful. Payment error: {$check['message']}";
            }
        }

        $order = Order::create($data);

        SendOrderMail::dispatch($order, SendOrderMail::ORDER_CONFIRMED);

        if (($data['payment_confirmed'] ?? 0) == 1) {

            SendOrderMail::dispatch($order, SendOrderMail::ORDER_PAYMENT_RECEIVED);
        }

        return [
            'status' => true,
            'message' => $respMsg,
            'order' => $order->load(['items', 'location', 'pickup_location']),
            'payment_confirmed' => ($data['payment_confirmed'] ?? 0)
        ];
    }

    public function checkoutSummary(Request $request)
    {
        $request->validate([
            'cart_uuid' => 'required|exists:carts',
            'delivery_method' => 'nullable|string|in:shipping,pickup',
            'delivery_type' => 'required_if:delivery_method,shipping|string|in:standard,same_day,next_day',
            'location_id' => 'required_if:delivery_method,shipping|numeric|exists:locations,id',
            'coupon_code' => 'nullable|exists:coupons,code',
            'add_prescription_charge' => 'nullable|in:yes,no'
        ]);

       

   

        $subTotal = Cart::where('cart_uuid', $request->cart_uuid)->sum('price');

        $return = ['sub_total' => $subTotal];
        $total = $subTotal;

        if ($request->location_id && $request->delivery_method === 'shipping') {
            $location = Location::find($request->location_id);
            $delType = "{$request->delivery_type}_price";
            $deliveryCost =  $location->{$delType};

            $total = $subTotal + $deliveryCost;
            $return['delivery'] = $deliveryCost;
        }


        $return['total'] = $total;
        // if ($request->add_prescription_charge === 'yes') {
        //     $return['total'] = $return['total'] + 1000;
        //     $return['prescription_charge'] = 1000;
        // }
       

        if ($request->coupon_code) {
            $return['discount'] = $this->computeValue($request->coupon_code, $subTotal);
            $return['total'] = $return['total'] - $return['discount'];
        }


       

        // Paystack transaction charge
        $charges = $return['total'] * 0.015;
        if ($return['total'] > 2500) {
            $charges = $charges + 100;
        }
        if ($charges > 2000) {
            $charges = 2000;
        }

        $return['transaction_charge'] = round($charges, 2);
        $return['total'] = $return['total'] + round($charges, 2);

        
        return $return;
    }


    public function confirmPayment(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'order_ref' => 'required|uuid|exists:orders,order_ref'
        ]);

        if ($validator->fails()) {
            return response(['status' => false, 'message' => $validator->errors()], 422);
        }

        $order = Order::where($validator->validated())->first();

        if (empty($order)) {

            return response([
                'status' => false,
                'message' => 'Sorry, no order was found with that reference code'
            ], 422);
        }

        $order->payment_confirmed = 1;
        $order->save();

        SendOrderMail::dispatch($order, SendOrderMail::ORDER_PAYMENT_RECEIVED);

        $isCleanOrder = true;
        $items = [];
        $itemIds = [];

        foreach ($order->items as $item) {

            $item->drug->update(['quantity' => (int) ($item->drug->quantity - $item->quantity)]);

            if ($item->drug->require_prescription == 1 && empty($item->prescription)) {
                $isCleanOrder = false;
                break;
            }
            $itemIds[] = $item->id;
            $items[] = [
                'id' => $item->id,
                'name' => $item->drug->name,
                'quantity' => $item->quantity
            ];
        }

        if ($isCleanOrder) {
            Cart::whereIn('id', $itemIds)->update(['status' => 'approved']);
            //$order->items->update(['status' => 'approved']); throws an error: collection doesn't have an update method
            $agents = [];
            foreach (($order->location->pharmacies ?? []) as $pharmacy) {
                foreach ($pharmacy->agents as $agent) $agents[] = $agent->device_token;
            }
            if (!empty($agents)) {
                $resp = $this->sendNotification(
                    $agents,
                    "New Order",
                    "Hello there! there's been a new approved order for your location with Order REF: {$order->order_ref}",
                    'high',
                    ['orderId' => $order->id, 'items' => $items]
                );
                //return response($resp, 400);
            }
        }
        //return response('No agents', 400);

        return [
            'status' => true,
            'message' => 'Thank you. Your payment has been confirmed'
        ];
    }

    public function mergeOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_cart_uuid' => 'required|uuid|exists:orders,cart_uuid',
            'new_cart_uuid' => 'required|uuid|exists:carts,cart_uuid',
        ]);

        if ($validator->fails()) {
            return response([
                'status' => false,
                'message' => $validator->errors()
            ], 422);
        }

        $cart = Cart::where('cart_uuid', $request->new_cart_uuid);
        $cart->update([
            'cart_uuid' => $request->old_cart_uuid
        ]);

        Order::where('cart_uuid', $request->new_cart_uuid)->delete();

        return [
            'status' => true,
            'cart_uuid' => $request->old_cart_uuid,
            'message' => 'Cart merged successfully'
        ];
    }

    public function cancelOrder(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'cart_uuid' => 'required|uuid|exists:orders,cart_uuid'
        ]);

        if ($validator->fails()) {
            return response([
                'status' => false,
                'message' => $validator->errors()
            ], 422);
        }

        $order = Order::where($validator->validated())->first();
        if (!empty($order)) {
            foreach ($order->items as $item) $item->delete();
            $order->delete();
        } else Cart::where($validator->validated())->delete();

        return [
            'status' => true,
            'order_ref' => $order->order_ref,
            'message' => 'Order cancelled successfully'
        ];
    }


    public function viewOrder(Order $order)
    {
        $user = Auth::user();
        if ($order->email != $order->email && $order->customer_id != $user->id) {
            return response(['error' => 'Unauthorized'], 401);
        }

        $order->load(['items.drug', 'location', 'pickup_location']);

        return $order;
    }

}
