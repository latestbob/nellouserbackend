<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendOrderMail;
use App\Models\Cart;
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
            'address2' => 'nullable|string',
            'city' => 'required|string',
            'region' => 'required|string',
            'postal_code' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()]);
        }

        $check = Order::where(['cart_uuid' => $request->cart_uuid])->first();

        if (!empty($check)) {
            return response(['message' => [["You already checked out those cart items"]]]);
        }

        $data = $validator->validated();
        $data['email'] = $request->email;

        $userID = null;

        if (!empty($request->userID)) $userID = $request->userID;
        elseif (!empty($request->password)) {

            $check = User::where(['email' => $request->email])->first();

            if (!empty($check)) {
                return response(['message' => "The email has already been taken."]);
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

        $data['state'] = $data['region'];

        if ($userID) $data['customer_id'] = $userID;

        $data['order_ref'] = strtoupper(Str::uuid()->toString());
        $cart = Cart::where('cart_uuid', $data['cart_uuid']);
        $data['amount'] = $cart->sum('price');

        $order = Order::create($data);

        SendOrderMail::dispatch($order, $request->email);

        return [
            'message' => 'Checkout successful',
            'order' => $order
        ];
    }
}
