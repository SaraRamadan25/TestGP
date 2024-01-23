<?php

namespace App\Http\Controllers;

use App\Models\Health;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HealthController extends Controller
{
    public function saveHealthData(Request $request): JsonResponse
    {
        $data = $request->all();

        $model = Health::create($data);

        $response = [
            'message' => 'Data saved successfully',
            'id' => $model->id,
        ];

        return response()->json($response);
    }
}
