<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;
use App\Http\Resources\Review\ReviewCollection;

class ReviewController extends Controller
{
    public function index(): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        $reviews = Review::where('user_id', $user->id)->get();

        if ($reviews->isEmpty()) {
            return response()->json(['message' => 'No reviews found for this user'], 404);
        }

        return new JsonResponse(new ReviewCollection($reviews), 200);
    }
}
