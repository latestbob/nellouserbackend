<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Cart;
use App\Models\Order;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class OrderController extends Controller
{

    public function __construct()
    {
        Config::set('auth.providers', ['users' => [
            'driver' => 'eloquent',
            'model' => Admin::class,
        ]]);
    }

    public function drugOrders(Request $request) {

        try {
            $admin = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof TokenInvalidException){
                return response()->json(['status' => 'Token is Invalid'], 401);
            }else if ($e instanceof TokenExpiredException){
                return response()->json(['status' => 'Token is Expired'], 401);
            }else{
                return response()->json(['status' => 'Authorization Token not found'], 401);
            }
        }

        $size = empty($request->size) ? 1 : $request->size;

        $orders = Order::with(['items' => function($query) use ($admin) {
            $query->where('carts.vendor_id', $admin->vendor_id)
                ->selectRaw("ROUND(SUM(carts.price), 2) as amount");
        }])->orderBy('id', 'desc')->paginate();

        return $orders;

        $orders = Order::query()->join('carts', 'orders.cart_uuid', '=',
            'carts.cart_uuid', 'INNER')->leftJoin('users', 'orders.customer_id', '=', 'users.id')
            ->where('carts.vendor_id', $admin->vendor_id)
            ->select(['*', 'orders.firstname', 'orders.lastname', 'orders.id',
                'orders.phone', 'orders.email', 'orders.city', 'orders.state', 'orders.created_at'])
            ->selectRaw("ROUND(SUM(carts.price), 2) as amount")
            ->groupBy('carts.cart_uuid')->orderByDesc('orders.id')->paginate($size);

        return $orders;
    }

    public function drugOrderItems(Request $request) {

        try {
            $admin = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof TokenInvalidException){
                return response()->json(['status' => 'Token is Invalid'], 401);
            }else if ($e instanceof TokenExpiredException){
                return response()->json(['status' => 'Token is Expired'], 401);
            }else{
                return response()->json(['status' => 'Authorization Token not found'], 401);
            }
        }

        if (empty($request->cart_uuid)) {
            return response(['message' => ['Cart ID is missing']], 401);
        }

        $size = empty($request->size) ? 1 : $request->size;

        $items = Cart::with('drug')->where(['cart_uuid' => $request->cart_uuid, 'vendor_id' => $admin->vendor_id])
            ->orderByDesc('id')->paginate($size);

        return $items;
    }

    public function drugOrderItemAction(Request $request) {

        try {
            $admin = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof TokenInvalidException){
                return response()->json(['status' => 'Token is Invalid'], 401);
            }else if ($e instanceof TokenExpiredException){
                return response()->json(['status' => 'Token is Expired'], 401);
            }else{
                return response()->json(['status' => 'Authorization Token not found'], 401);
            }
        }

        $validator = Validator::make($request->all(), [
            'cartID' => 'required|numeric|exists:carts,id',
            'status' => 'required|string|in:approved,disapproved,cancelled',
        ]);

        if ($validator->fails()) {
            return response([
                'message' => $validator->errors()
            ], 400);
        }

        if (empty($request->cartID)) {
            return response(['message' => [['Cart ID is missing']]], 401);
        }

        $item = Cart::where(['id' => $request->cartID, 'vendor_id' => $admin->vendor_id])->first();

        if (empty($item)) {
            return response()->json(['message' => [['Sorry, that item was not found']]], 401);
        }

        $item->status = $request->status;
        $item->save();

        return response()->json(['message' => "That order item has been {$request->status} successfully"]);
    }
}
