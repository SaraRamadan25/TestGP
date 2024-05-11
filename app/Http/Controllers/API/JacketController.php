<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\JacketResource;
use App\Models\Guard;
use App\Models\Jacket;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class JacketController extends Controller
{
    public function check($modelNo): JsonResponse
    {
        $jacket = Jacket::where('modelno', $modelNo)->first();

        if ($jacket) {
            return response()->json([
                'exists' => true,
                'jacket' => [
                    'modelno' => $jacket->modelno,
                    'batteryLevel' => $jacket->batteryLevel,
                    'start_rent_time' => $jacket->start_rent_time,
                    'end_rent_time' => $jacket->end_rent_time,
                    'user_id' => $jacket->user_id,
                    ],
            ]);
        } else {
            return response()->json([
                'exists' => false,
            ]);
        }
    }
    public function index($guardId): JsonResponse
    {
        try {
            $guard = Guard::findOrFail($guardId);
            $jackets = $guard->jackets;
            return response()->json($jackets);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    }
    public function activeJackets($guardId): JsonResponse
    {
        try {
            $guard = Guard::findOrFail($guardId);
            $activeJackets = $guard->jackets()->where('active', '1')->get();
            $count = $activeJackets->count();

            return response()->json([
                'count' => $count,
                'jackets' => $activeJackets,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    }}
