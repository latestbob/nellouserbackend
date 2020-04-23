<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendOrderMail;
use App\Models\Cart;
use App\Models\Locations;
use App\Models\Order;
use App\Models\User;
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
            'address1' => 'required|string',
            'location_id' => 'required|numeric|exists:locations,id',
            'city' => 'required|string',
            'state' => 'required|string',
//            'postal_code' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()]);
        }

        $data = $validator->validated();
        $data['email'] = $request->email;

        $order = Order::where(['cart_uuid' => $request->cart_uuid])->first();

        if (!empty($order)) {

            if ($order->payment_confirmed == 1)
                return response(['message' => [["You already checked out and made payment for those cart items"]]]);
            else {

                $data['order_ref'] = strtoupper(Str::uuid()->toString());
                $cart = Cart::where('cart_uuid', $data['cart_uuid']);
                $location = Locations::where('id', $data['location_id'])->first();
//                $data['amount'] = round((($subTotal = $cart->sum('price')) + 500 + (($subTotal / 100) * 7.5)));
                $data['amount'] = round((($subTotal = $cart->sum('price')) + ($location->price + 500)));

                $order->update($data);

                SendOrderMail::dispatch($order, $request->email, SendOrderMail::ORDER_CONFIRMED);

                return [
                    'message' => 'Checkout successful',
                    'order' => $order
                ];
            }
        }

        $userID = null;

        if (!empty($request->userID)) $userID = $request->userID;
        elseif (!empty($request->password)) {

            $check = User::where(['email' => $request->email])->first();

            if (!empty($check)) {
                return response(['message' => [["The email has already been taken."]]]);
            }

            $user = User::create([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'email' => $request->email,
                'phone' => $request->phone,
                'vendor_id' => $request->facilityID,
                'user_type' => 'customer',
                'uuid' => Str::uuid()->toString(),
                'password' => Hash::make($request->password)
            ]);

            if ($user) $userID = $user->id;
        }

        if ($userID) $data['customer_id'] = $userID;

        $data['order_ref'] = strtoupper(Str::uuid()->toString());
        $cart = Cart::where('cart_uuid', $data['cart_uuid']);
        $data['amount'] = round((($subTotal = $cart->sum('price')) + 500 + (($subTotal / 100) * 7.5)));

        $order = Order::create($data);

        SendOrderMail::dispatch($order, $request->email, SendOrderMail::ORDER_CONFIRMED);

        return [
            'message' => 'Checkout successful',
            'order' => $order
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

        SendOrderMail::dispatch($order, $request->email, SendOrderMail::ORDER_PAYMENT_RECEIVED);

        return [
            'message' => 'Thank you. Your payment has been confirmed'
        ];
    }
}
