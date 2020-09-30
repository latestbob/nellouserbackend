<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        return Wishlist::with(['drug'])->where('user_id', $request->user()->id)->get();
    }

    public function addWishlist(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'drug_id' => 'required|integer|exists:pharmacy_drugs,id'
        ]);

        if ($validator->fails()) {
            return response([
                'status' => false,
                'message' => $validator->errors()
            ]);
        }

        $data = $validator->validated();
        $data['user_id'] = $request->user()->id;

        $wishlist = Wishlist::where($data)->first();

        if (!empty($wishlist)) {

            $wishlist->load('drug');

            return response()->json([
                'status' => false,
                'message' => 'Item already exist in your wishlist',
                'item' => $wishlist
            ]);

        }

        $wishlist = Wishlist::create($data);

        $wishlist->load('drug');

        return response()->json([
            'status' => true,
            'message' => 'Added successfully to wishlist',
            'item' => $wishlist
        ]);
    }

    public function removeWishlist(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'drug_id' => 'required|integer|exists:pharmacy_drugs,id'
        ]);

        if ($validator->fails()) {
            return response([
                'status' => false,
                'message' => $validator->errors()
            ]);
        }

        $data = $validator->validated();
        $data['user_id'] = $request->user()->id;

        $wishlist = Wishlist::where($data)->first();

        if (empty($wishlist)) {

            return response()->json([
                'status' => false,
                'message' => 'Item doest not exist in your wishlist'
            ]);

        }

        $wishlist->delete();

        return response()->json([
            'status' => true,
            'message' => 'Removed successfully from wishlist'
        ]);
    }
}
