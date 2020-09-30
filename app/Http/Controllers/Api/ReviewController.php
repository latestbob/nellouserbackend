<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        return Review::with('user')->where(
            'drug_uuid', $request->uuid)->orderByDesc('id')->paginate();
    }

    public function recent(Request $request)
    {
        return [
            'recent' => Review::with('user')->where(
                'drug_uuid', $request->uuid)->orderByDesc('id')->limit(4)->get()
        ];
    }

    public function addReview(Request $request)
    {

        $validated = Validator::make($request->all(), [
            'drug_uuid' => 'required|uuid|exists:pharmacy_drugs,uuid',
            'name'  => 'required|string',
            'review'  => 'required|string',
            'rating'  => 'required|numeric|min:1|max:5',
        ]);

        if ($validated->fails()) {
            return response(['message' => $validated->errors()]);
        }

        $validated = $validated->validated();

        $validated['user_id'] = Auth::check() ? $request->user()->id : null;

        $review = Review::create($validated);

        if (!$review) {
            return response(['message' => [['Sorry we could post your review at this time, please try again later']]]);
        }

        return [
            'message' => 'Review posted successfully',
            'review' => $review
        ];

    }
}
