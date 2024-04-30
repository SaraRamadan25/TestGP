<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSessionRequest;
use App\Models\Session;

class SessionController extends Controller
{
        public function index()
    {
        $userId = auth()->id();
        $sessions = Session::where('user_id', $userId)->paginate(5);
        return response()->json($sessions);
    }

    public function store(StoreSessionRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['user_id'] = auth()->id();

        $session = Session::create($validatedData);
        return response()->json($session, 201);
    }

    public function destroy(Session $session)
    {
        if (auth()->user()->id !== $session->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $session->delete();
        return response()->json(null, 204);
    }
}
