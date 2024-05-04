<?php

namespace App\Http\Controllers\API;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSessionRequest;
use App\Models\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class SessionController extends Controller
{
    // For Trainers Only
    public function TrainerAllSessions(){
        if (auth()->user()->role != 'trainer') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $sessions = Session::where('trainer_id', auth()->id())->paginate(5);
        return response()->json($sessions, 200);
    }
    public function store(StoreSessionRequest $request)
    {
        if (auth()->user()->role != 'trainer') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $validatedData = $request->validated();
        $validatedData['user_id'] = auth()->id();

        $session = Session::create($validatedData);
        return response()->json($session, 201);
    }
    public function destroy(Session $session)
    {
        if (auth()->user()->role != 'trainer') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        if (auth()->user()->id !== $session->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $session->delete();
        return response()->json(null, 204);
    }

    // For Parents Only
    public function bookSession(Request $request, Session $session)
    {
        $user = $request->user(); // Get the currently authenticated user

        if ($session->user_id) {
            return response()->json(['message' => 'Session already booked'], 400);
        }

        $session->user_id = $user->id;
        $session->save();

        return response()->json(['message' => 'Session booked successfully'], 200);
    }
    public function bookedSessions(Request $request)
    {
        $user = $request->user();

        $sessions = Session::where('user_id', $user->id)->get();
        return response()->json($sessions, 200);
    }
    public function CancelSession(Request $request, $sessionId)
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
