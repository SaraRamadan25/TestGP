<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    public function destroy(User $user): JsonResponse
    {
        $user->delete();
        return response()->json([
            'message' => 'User deleted successfully',
        ], 204);
    }
}
