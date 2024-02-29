<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function show(User $user): UserResource|JsonResponse
    {
        if (Auth::check()) {
            return new UserResource($user);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }

    public function destroy(User $user): JsonResponse
    {
        if (Auth::check()) {
            $user->delete();
            return response()->json([
                'message' => 'User deleted successfully',
            ], 204);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }
}
