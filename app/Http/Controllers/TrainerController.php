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
        if (Auth::check()) {
            $trainers = Trainer::paginate(5)->with('sessions');
            return response()->json($trainers);
        }
        return response()->json(['message' => 'Unauthorized'], 401);
    }


    public function store(StoreTrainerRequest $request)
    {
        if (\App\Enums\Role::Trainer || \App\Enums\Role::Admin) {
            $trainer = Trainer::create($request->validated());
            return response()->json($trainer, 201);
        }
        return response()->json(['message' => 'Unauthorized'], 401);
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Trainer $trainer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTrainerRequest $request, Trainer $trainer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Trainer $trainer)
    {
        //
    }
}
