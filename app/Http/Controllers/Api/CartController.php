<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\PharmacyDrug;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function getItems(Request $request) {
        return Cart::with(['drug'])->where(['cart_uuid' => $request->cart_uuid])->get();
    }

    public function addToCart(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'drug_id' => 'required|integer',
            'quantity' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }

        $data = $validator->validated();

        $drug = PharmacyDrug::find($data['drug_id']);

        if (empty($drug)) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to add to cart. Drug not found'
            ]);
        }

        if (!isset($data['quantity'])) {
            $data['quantity'] = 1;
        }

        $data['price'] = $drug->price * $data['quantity'];

        if (empty($request->cart_uuid)) {
            $request->cart_uuid = Str::uuid()->toString();
        }

        $data['cart_uuid'] = $request->cart_uuid;

        $data['user_id'] = Auth::check() ? $request->user()->id : 0;

        $cart = Cart::where(['drug_id' => $data['drug_id'], 'cart_uuid' => $data['cart_uuid']])->first();

        if (empty($cart)) {
            $cart = Cart::create($data);
        } else {
            $cart->quantity = $cart->quantity + $data['quantity'];
            $cart->price = $cart->price + $data['price'];
            $cart->save();
        }

        return response()->json([
            'status' => true,
            'message' => 'Added to cart successfully',
            'cart_uuid' => $request->cart_uuid,
            'cart' => $cart
        ]);
    }

    public function updateCart(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'cart_uuid' => 'required|string',
            'drug_id' => 'required|integer',
            'quantity'  => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }

        $data = $validator->validated();

        $cart_uuid = $request->cart_uuid;
        $drug = PharmacyDrug::find($data['drug_id']);

        if (empty($drug)) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update cart. Drug not found'
            ]);
        }

        $cart = Cart::where(['drug_id' => $data['drug_id'], 'cart_uuid' => $cart_uuid])->first();

        if (empty($cart)) {
            return $this->addToCart($request);

            /*return response()->json([
                'status' => false,
                'message' => 'Failed to update cart. Item not found in cart'
            ]);*/
        }

        if ($request->type == 'addQuantity') $cart->quantity = $cart->quantity + 1;
        else $cart->quantity = $cart->quantity - 1;

        $cart->price = $drug->price * $cart->quantity;

        $cart->save();

        return response()->json([
            'status' => true,
            'message' => 'Cart updated successfully',
            'cart_uuid' => $request->cart_uuid,
            'cart' => $cart
        ]);
    }

    public function removeFromCart(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'cart_uuid' => 'required|string',
            'drug_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }

        Cart::where([
            'cart_uuid' => $request->cart_uuid,
            'drug_id'   => $request->drug_id
        ])->delete();
        return ['message' => 'Removed'];
    }
}
