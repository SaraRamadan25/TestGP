<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\District;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    public function getDistricts(City $city): JsonResponse
    {
        $districts = District::where('city_id', $city->id)->get();
        return response()->json($districts);
    }

    public function getAllDistricts(): JsonResponse
    {
        $districts = District::all();
        return response()->json($districts);
    }
}
