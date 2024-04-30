<?php

namespace App\Http\Controllers\API;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\User;

class TrainerController extends Controller
{
    public function index()
    {
        $trainerRole = Role::Trainer;

        $trainers = User::whereHas('roles', function ($query) use ($trainerRole) {
            $query->where('name', $trainerRole);
        })->get();

        return response()->json(['trainers' => $trainers], 200);
    }
}
