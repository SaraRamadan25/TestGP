<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function getCities(): JsonResponse
    {
        $cities = City::all();
        return response()->json($cities);
    }
}
