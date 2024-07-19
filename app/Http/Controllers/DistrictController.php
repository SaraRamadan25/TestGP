<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\District;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    public function getDistricts($cityId): JsonResponse
    {
        $districts = District::where('city_id', $cityId)->get();
        return response()->json($districts);
    }
    public function getAllDistricts(): JsonResponse
    {
        $districts = District::all();
        return response()->json($districts);
    }
}
