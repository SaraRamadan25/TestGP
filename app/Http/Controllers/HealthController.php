<?php

namespace App\Http\Controllers;

use App\Models\Health;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HealthController extends Controller
{
    public function submitHealthData(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|numeric',
            'height' => 'required|numeric',
            'weight' => 'required|numeric',
            'heart_rate' => 'required|numeric',
            'blood_type' => 'required|string',
            'diseases' => 'nullable|string',
            'allergies' => 'nullable|string',
        ]);

       $validatedData['user_id'] = Auth::id();
        Health::create($validatedData);

        return response()->json(['message' => 'Health data successfully submitted'], 200);
    }
}
