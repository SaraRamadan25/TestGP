<?php

namespace App\Http\Controllers;

use App\Http\Resources\TrainerResource;
use App\Models\Role;
use App\Models\Trainer;
use App\Http\Requests\StoreTrainerRequest;
use App\Http\Requests\UpdateTrainerRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class TrainerController extends Controller
{
    public function index(): JsonResponse
    {
        if (auth()->check())
        {
            $trainers = Trainer::with('sessions')->paginate(5);
            return response()->json($trainers);
        }
        return response()->json(['message' => 'Unauthenticated'], 401);
    }


}
