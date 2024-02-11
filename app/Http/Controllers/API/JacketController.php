<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\JacketResource;
use App\Models\Jacket;
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

    public function index(): JsonResponse
    {
        $jackets = Jacket::all();
        return response()->json($jackets);
    }

    public function show(Jacket $jacket): JacketResource
    {
        return new JacketResource($jacket);
    }

    public function moderate(): JsonResponse
    {
        $jackets = Jacket::where('active', '1')->get();
        return response()->json($jackets);
    }
}
