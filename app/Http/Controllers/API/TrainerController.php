<?php

namespace App\Http\Controllers\API;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\Session;
use App\Models\Trainer;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class TrainerController extends Controller
{
    public function availableTrainers(): JsonResponse
    {
        if (!auth()->check())
        {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        else
         {
            $trainers = Trainer::with('sessions')->paginate(5);
            return response()->json($trainers);
        }
    }
}
