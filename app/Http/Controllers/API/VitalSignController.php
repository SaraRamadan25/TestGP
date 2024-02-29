<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\VitalSignResource;
use App\Models\Jacket;
use App\Models\VitalSign;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VitalSignController extends Controller
{
    public function show(Jacket $jacket) :VitalSignResource|JsonResponse
    {
        $user = auth()->user();

        if ($user->id !== $jacket->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $vitalSign = $jacket->vitalSign;
        return new VitalSignResource($vitalSign);
    }
}
