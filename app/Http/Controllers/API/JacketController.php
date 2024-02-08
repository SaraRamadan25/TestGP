<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
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


    public function manage()
    {
        $jackets = Jacket::all();
        return response()->json($jackets);
    }

    public function view()
    {
        $jackets = Jacket::where('modelno', '1234')->get();
        return response()->json($jackets);
    }

    public function moderate()
    {
        $jackets = Jacket::where('modelno', '4444')->get();
        return response()->json($jackets);
    }
}
