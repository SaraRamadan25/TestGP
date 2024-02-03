<?php

namespace App\Http\Controllers;

use App\Http\Requests\JacketRequest;
use App\Models\Jacket;
use App\Models\QrCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
    }}
