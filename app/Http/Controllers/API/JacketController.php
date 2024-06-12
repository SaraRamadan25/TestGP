<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\JacketResource;
use App\Models\Guard;
use App\Models\Jacket;
use GPBMetadata\Google\Api\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class JacketController extends Controller
{
    public function __construct()
    {
        $this->middleware('guard')->only('index');
    }

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
    public function index(Guard $guard): JsonResponse
    {
        $jackets = $guard->jackets;

        if ($jackets->isEmpty()) {
            return response()->json(['message' => 'No jackets related to this guard']);
        }

        return response()->json($jackets);
    }

    public function activeJackets(Guard $guard): JsonResponse
    {
        $activeJackets = $guard->jackets->where('active', true);

        if ($activeJackets->isEmpty()) {
            return response()->json(['message' => 'No active jackets related to this guard']);
        }

        return response()->json($activeJackets);
    }
}
