<?php

namespace App\Http\Controllers\API;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\Session;
use App\Models\Trainer;
use App\Models\User;

class TrainerController extends Controller
{
    public function indexUser()
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        if (auth()->user()->role == 'parent') {
            $trainers = Trainer::with('sessions')->paginate(5);
            return response()->json($trainers);
        } else {
            return response()->json(['message' => 'Access denied'], 403);
        }
    }
}
