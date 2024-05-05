<?php

namespace App\Http\Controllers\API;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Resources\SessionResource;
use App\Http\Resources\UserResource;
use App\Models\Session;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

    public function UserSessions(User $user)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $sessions = Session::where('user_id', $user->id)->get();

        if ($sessions->isEmpty()) {
            return response()->json(['message' => 'No sessions found'], 404);
        }

        return response()->json(['sessions' => SessionResource::collection($sessions)], 200);
    }

}
