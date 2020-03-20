<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\PharmacyDrug;
use App\Traits\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CartController extends Controller
{

    use FileUpload;

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
            return response(['message' => $validator->errors()]);
        }

        $data = $validator->validated();

        $drug = PharmacyDrug::where(['id' => $data['drug_id']])->first();

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
            $request->cart_uuid = strtolower(Str::uuid()->toString());
        }

        $data['vendor_id'] = $drug->vendor_id;

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
            return response(['message' => $validator->errors()]);
        }

        $data = $validator->validated();

        $cart_uuid = $request->cart_uuid;
        $drug = PharmacyDrug::where(['id' => $data['drug_id']])->first();

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
            return response(['message' => $validator->errors()]);
        }

        Cart::where([
            'cart_uuid' => $request->cart_uuid,
            'drug_id'   => $request->drug_id
        ])->delete();
        return ['message' => 'Removed'];
    }

    public function addPrescription(Request $request) {

        $validator = Validator::make($request->all(), [
            'cart_uuid' => 'required|string',
            'drug_id' => 'required|integer',
            'file' => 'required|image|mimes:jpeg,jpg,png',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()]);
        }

        $item = Cart::where(['cart_uuid' => $request->cart_uuid, 'drug_id' => $request->drug_id])->first();

        if (empty($item)) {

            return response(['message' => "Failed to add prescription, item not found"]);
        }

        if ($request->hasFile('file')) {

//            $item->prescription = $prescription = $this->uploadFile($request, 'file');
            $item->prescription = $prescription = "https://localhost/personal/wizdom/admin/storage/blog/image/bc872aacca0ce8d19204dc3cd5570797a7767b16.jpg";
            $item->save();

            return response(['message' => "Prescription uploaded and added successfully", 'prescription' => $prescription]);

        } else return response(['message' => "No prescription file uploaded"]);
    }
}
