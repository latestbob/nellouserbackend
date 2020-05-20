<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendOrderMail;
use App\Models\Cart;
use App\Models\CustomerPointRules;
use App\Models\CustomerPoints;
use App\Models\Locations;
use App\Models\Order;
use App\Models\User;
use App\Notifications\VerificationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class OrderController extends Controller
{

    public function checkout(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'company' => 'nullable|string',
            'phone' => 'required|numeric',
            'cart_uuid' => 'required|string|exists:carts,cart_uuid',
            'address1' => ($request->deliveryMethod == 'pickup' ? 'nullable' : 'required') . '|string',
            'location_id' => 'required|numeric|exists:locations,id',
            'city' => ($request->deliveryMethod == 'pickup' ? 'nullable' : 'required') . '|string',
//            'state' => ($request->deliveryMethod == 'pickup' ? 'nullable' : 'required') . '|string',
            'paymentMethod' => 'required|string|in:card,point'
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()]);
        }

        $data = $validator->validated();
        $data['email'] = $request->email;

        if (!empty($request->userID)) $data['customer_id'] = $request->userID;

        $order = Order::where(['cart_uuid' => $request->cart_uuid])->first();

        if (!empty($order)) {

            if ($order->payment_confirmed == 1)
                return response(['message' => [["You already checked out and made payment for those cart items"]]]);
            else {

                $data['order_ref'] = strtoupper(Str::uuid()->toString());
                $cart = Cart::where('cart_uuid', $data['cart_uuid']);
                $location = Locations::where('id', $data['location_id'])->first();
                //$data['amount'] = round((($subTotal = $cart->sum('price')) + 500 + (($subTotal / 100) * 7.5)));
                $data['amount'] = round((($subTotal = $cart->sum('price')) + $location->price));

                if ($data['paymentMethod'] == 'point') {

                    if (!isset($data['customer_id'])) return response(['message' => [['You must be logged in to pay with points']]]);

                    $point = CustomerPoints::where('customer_id', $data['customer_id'])->first();

                    if (empty($point)) return response(['message' => [['You have not earned any points yet']]]);

                    $rules = CustomerPointRules::orderByDesc('id')->limit(1)->first();

                    if (empty($rules)) return response(['message' => [['Point rules have not been set yet, contact system administrator']]]);

                    $pointValue = ($rules['point_value'] * $point['point']);

                    if ($pointValue < $data['amount']) return response(['message' => [["You don't have enough points to purchase the items in your cart"]]]);

                    $remainingPoints = (($pointValue - $data['amount']) / $rules['point_value']);

                    $point->point = ceil($remainingPoints);
                    $point->save();

                    $data['payment_confirmed'] = 1;
                    $data['payment_method'] = "Point";

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

        if (!isset($data['customer_id']) && !empty($request->password)) {

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

        $data['order_ref'] = strtoupper(Str::uuid()->toString());
        $cart = Cart::where('cart_uuid', $data['cart_uuid']);
        //$data['amount'] = round((($subTotal = $cart->sum('price')) + 500 + (($subTotal / 100) * 7.5)));
        $location = Locations::where('id', $data['location_id'])->first();
        //$data['amount'] = round((($subTotal = $cart->sum('price')) + 500 + (($subTotal / 100) * 7.5)));
        $data['amount'] = round((($subTotal = $cart->sum('price')) + $location->price));

        if ($data['paymentMethod'] == 'point') {

            if (!isset($data['customer_id'])) return response(['message' => [['You must be logged in to pay with points']]]);

            $point = CustomerPoints::where('customer_id', $data['customer_id'])->first();

            if (empty($point)) return response(['message' => [['You have not earned any points yet']]]);

            $rules = CustomerPointRules::orderByDesc('id')->limit(1)->first();

            if (empty($rules)) return response(['message' => [['Point rules have not been set yet, contact system administrator']]]);

            $pointValue = ($rules['point_value'] * $point['point']);

            if ($pointValue < $data['amount']) return response(['message' => [["You don't have enough points to purchase the items in your cart"]]]);

            $remainingPoints = (($pointValue - $data['amount']) / $rules['point_value']);

            $point->point = ceil($remainingPoints);
            $point->save();

            $data['payment_confirmed'] = 1;
            $data['payment_method'] = "Point";

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
            'reference' => 'required|string|exists:orders,order_ref'
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()]);
        }

        $order = Order::where(['order_ref' => $request->reference])->first();

        if (empty($order)) {

            return [
                'message' => 'Sorry, no order was found with that reference code'
            ];
        }

        $order->payment_confirmed = 1;
        $order->payment_method = "Paystack";
        $order->save();

        if (is_numeric($order->customer_id)) {

            $rules = CustomerPointRules::orderByDesc('id')->limit(1)->first();

            if (!empty($rules) && ($order->amount ?? 0) > $rules['earn_point_amount']) {

                $point = CustomerPoints::where('customer_id', $order->customer_id)->first();

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

                } else CustomerPoints::create(['point' => 1, 'total_points_earned_today' => 1, 'customer_id' => $order->customer_id]);

            }
        }

        SendOrderMail::dispatch($order, SendOrderMail::ORDER_PAYMENT_RECEIVED);

        return [
            'message' => 'Thank you. Your payment has been confirmed'
        ];
    }
}
