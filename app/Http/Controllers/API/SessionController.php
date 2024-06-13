<?php

namespace App\Http\Controllers\API;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSessionRequest;
use App\Models\Session;
use App\Models\Trainer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SessionController extends Controller
{
    // For Trainers Only
    public function TrainerAllSessions(Trainer $trainer): JsonResponse
    {
        $sessions = Session::where('trainer_id', $trainer->id)->paginate(5);

        return response()->json($sessions);
    }
    public function store(StoreSessionRequest $request, Trainer $trainer): JsonResponse
    {
        if (!DB::table('trainers')->where('username', $trainer->username)->exists()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validatedData = $request->validated();
        $validatedData['trainer_id'] = $trainer->id;

        $session = Session::create($validatedData);
        return response()->json($session, 201);
    }
    public function destroy(Trainer $trainer, Session $session): JsonResponse
    {
        if (!DB::table('trainers')->where('username', $trainer->username)->exists()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($trainer->id !== $session->trainer_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $session->delete();
        return response()->json(null, 204);
    }
    // For Parents Only
    public function bookSession(Request $request, Session $session): JsonResponse
    {
        $user = $request->user();

        if ($session->user_id) {
            return response()->json(['message' => 'Session already booked'], 400);
        }

        $session->user_id = $user->id;
        $session->save();

        return response()->json(['message' => 'Session booked successfully'], 200);
    }
    public function CancelSession(Request $request, $sessionId): JsonResponse
    {
        $user = $request->user();

        $session = Session::find($sessionId);

        if (!$session) {
            return response()->json(['message' => 'Session not found'], 404);
        }

        if ($session->user_id != $user->id) {
            return response()->json(['message' => 'You can only cancel sessions that you have booked'], 400);
        }

        $session->user_id = null;
        $session->save();

        return response()->json(['message' => 'Session cancelled successfully'], 200);
    }
}
