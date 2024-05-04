<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGuardRequest;
use App\Http\Requests\UpdateGuardRequest;
use App\Models\Guard;

class GuardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGuardRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Guard $guard)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Guard $guard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGuardRequest $request, Guard $guard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Guard $guard)
    {
        //
    }
}
