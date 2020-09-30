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
use App\Models\User;
use App\Notifications\VerificationNotification;
use App\Traits\FirebaseNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    use FirebaseNotification;

    public function checkout(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'checkout_type' => 'required|string|in:user,guest,register',
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'company' => 'nullable|string',
            'email' => 'required|email',
            'password' => 'required_if:checkout_type,register|string',
            'phone' => 'required|digits_between:11,16',
            'cart_uuid' => 'required|uuid|exists:carts,cart_uuid',
            'delivery_method' => 'required|string|in:shipping,pickup',
            'address1' => 'required_if:delivery_method,shipping|string',
            'location_id' => 'required_without:pickup_location_id|numeric|exists:locations,id',
            'pickup_location_id' => 'required_without:location_id|numeric|exists:pharmacies,id',
            'city' => 'required_if:delivery_method,shipping|string',
            'payment_method' => 'required|string|in:card,point',
            'customer_id' => 'required_if:checkout_type,user|numeric|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()]);
        }

        $data = $validator->validated();
        $data['email'] = $request->email;

        if (empty($data['location_id'])) {
            $data['location_id'] = $data['pickup_location_id'];
        }

        $order = Order::where(['cart_uuid' => $request->cart_uuid])->first();
        $data['order_ref'] = strtoupper(Str::uuid()->toString());

        if (!empty($order)) {

            if ($order->payment_confirmed == 1) return response(['message' => [["You already checked out and made payment for those cart items"]]]);
            else {

                $cart = Cart::where('cart_uuid', $data['cart_uuid']);
                $data['amount'] = $cart->sum('price');

                if ($request->delivery_method == 'shipping') {
                    $location = Location::where('id', $data['location_id'] ?? 0)->first();
                    $data['amount'] += $location->price;
                }

                foreach ($cart->get() as $item) {
                    if ($item->drug->require_prescription == true && empty($item->prescription)) {
                        $data['amount'] += (PrescriptionFee::where('fee', '>', 0)->select('fee')->first() ?: (object)['fee' => 0])->fee;
                        break;
                    }
                }

                $data['amount'] = ceil($data['amount']);

                if ($data['payment_method'] == 'point') {

                    if (!isset($data['customer_id'])) return response(['message' => [['You must be logged in to pay with points']]]);

                    $point = CustomerPoint::where('customer_id', $data['customer_id'])->first();

                    if (empty($point)) return response(['message' => [['You have not earned any points yet']]]);

                    $rules = CustomerPointRule::orderByDesc('id')->limit(1)->first();

                    if (empty($rules)) return response(['message' => [['Point rules have not been set yet, contact system administrator']]]);

                    $pointValue = ($rules['point_value'] * $point['point']);

                    if ($pointValue < $data['amount']) return response(['message' => [["You don't have enough points to purchase the items in your cart"]]]);

                    $point->point = ceil((($pointValue - $data['amount']) / $rules['point_value']));
                    $point->save();

                    $data['payment_confirmed'] = 1;

                }

                $order->update($data);

                SendOrderMail::dispatch($order, SendOrderMail::ORDER_CONFIRMED);

                if (($data['payment_confirmed'] ?? 0) == 1) {

                    SendOrderMail::dispatch($order, SendOrderMail::ORDER_PAYMENT_RECEIVED);
                }

                return [
                    'message' => ($data['payment_confirmed'] ?? 0) == 1 ?
                        'Thank you. Your checkout was successful and payment has been made using your points' :
                        'Checkout successful',
                    'order' => $order,
                    'payment_confirmed' => ($data['payment_confirmed'] ?? 0)
                ];
            }
        }

        if ($data['checkout_type'] == 'register') {

            $check = User::where(['email' => $request->email])->first();

            if (!empty($check)) {
                return response(['message' => [["The email has already been taken."]]]);
            }

            $check = User::where(['phone' => $request->phone])->first();

            if (!empty($check)) {
                return response(['message' => [["The phone has already been taken."]]]);
            }

            $user = User::create([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'email' => $request->email,
                'phone' => $request->phone,
                'vendor_id' => $request->facilityID,
                'user_type' => 'customer',
                'uuid' => Str::uuid()->toString(),
                'token' => Str::random(15),
                'password' => bcrypt($request->password)
            ]);

            if ($user) {
                $data['customer_id'] = $user->id;
                $user->notify(new VerificationNotification());
            }
        }

        $cart = Cart::where('cart_uuid', $data['cart_uuid']);
        $data['amount'] = $cart->sum('price');

        if ($request->delivery_method == 'shipping') {
            $location = Location::where('id', $data['location_id'] ?? 0)->first();
            $data['amount'] +=  $location->price;
        }

        foreach ($cart->get() as $item) {
            if ($item->drug->require_prescription == true && empty($item->prescription)) {
                $data['amount'] += (PrescriptionFee::where('fee', '>', 0)->select('fee')->first() ?: (object)['fee' => 0])->fee;
                break;
            }
        }

        $data['amount'] = ceil($data['amount']);

        if ($data['payment_method'] == 'point') {

            if (!isset($data['customer_id'])) return response(['message' => [['You must be logged in to pay with points']]]);

            $point = CustomerPoint::where('customer_id', $data['customer_id'])->first();

            if (empty($point)) return response(['message' => [['You have not earned any points yet']]]);

            $rules = CustomerPointRule::orderByDesc('id')->limit(1)->first();

            if (empty($rules)) return response(['message' => [['Point rules have not been set yet, contact system administrator']]]);

            $pointValue = ($rules['point_value'] * $point['point']);

            if ($pointValue < $data['amount']) return response(['message' => [["You don't have enough points to purchase the items in your cart"]]]);

            $point->point = ceil((($pointValue - $data['amount']) / $rules['point_value']));
            $point->save();

            $data['payment_confirmed'] = 1;
        }

        $order = Order::create($data);

        SendOrderMail::dispatch($order, SendOrderMail::ORDER_CONFIRMED);

        if (($data['payment_confirmed'] ?? 0) == 1) {

            SendOrderMail::dispatch($order, SendOrderMail::ORDER_PAYMENT_RECEIVED);
        }

        return [
            'message' => ($data['payment_confirmed'] ?? 0) == 1 ?
                'Thank you. Your checkout was successful and payment has been made using your points' :
                'Checkout successful',
            'order' => $order,
            'payment_confirmed' => ($data['payment_confirmed'] ?? 0)
        ];
    }

    public function confirmPayment(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'order_ref' => 'required|uuid|exists:orders,order_ref'
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()]);
        }

        $order = Order::where($validator->validated())->first();

        if (empty($order)) {

            return [
                'message' => 'Sorry, no order was found with that reference code'
            ];
        }

        $order->payment_confirmed = 1;
        $order->save();

        if (is_numeric($order->customer_id)) {

            $rules = CustomerPointRule::orderByDesc('id')->limit(1)->first();

            if (!empty($rules) && ($order->amount ?? 0) > $rules['earn_point_amount']) {

                $point = CustomerPoint::where('customer_id', $order->customer_id)->first();

                if (!empty($point)) {

                    if (!empty($point->updated_at) && strtotime(date("Y-m-d")) > strtotime(\DateTime::createFromFormat(
                            "Y-m-d h:i:s", $point->updated_at)->format("Y-m-d"))) {

                        $point->total_points_earned_today = 0;
                    }

                    if ($point->total_points_earned_today < $rules->max_point_per_day) {
                        $point->point = ($point->point + 1);
                        $point->total_points_earned_today = ($point->total_points_earned_today + 1);
                        $point->save();
                    }

                } else CustomerPoint::create(['point' => 1, 'total_points_earned_today' => 1, 'customer_id' => $order->customer_id]);

            }
        }

        SendOrderMail::dispatch($order, SendOrderMail::ORDER_PAYMENT_RECEIVED);

        $isCleanOrder = true; $items = [];
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
                $resp = $this->sendNotification($agents, "New Order",
                    "Hello there! there's been a new approved order for your location with Order REF: {$order->order_ref}",
                    'high', ['orderId' => $order->id, 'items' => $items]);
                //return response($resp, 400); 
            }
        }
        //return response('No agents', 400); 

        return [
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
            ]);
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

    public function cancelOrder(Request $request) {

        $validator = Validator::make($request->all(), [
            'cart_uuid' => 'required|uuid|exists:orders,cart_uuid'
        ]);

        if ($validator->fails()) {
            return response([
                'status' => false,
                'message' => $validator->errors()
            ]);
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
}
