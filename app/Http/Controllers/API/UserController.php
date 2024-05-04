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
        $sessions = Session::where('user_id', $user->id)->get();
        return response()->json(['sessions' => SessionResource::collection($sessions)], 200);

    }

    public function DestroySession(Session $session): JsonResponse
    {
        if (Auth::check() && auth()->user()->role_id == Role::PARENT) {
            $session->hidden = true;
            $session->save();

            return response()->json(['message' => 'Session canceled successfully'], 200);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }
}
