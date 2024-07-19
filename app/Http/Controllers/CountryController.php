<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index(): JsonResponse
    {
        $countries = Country::with('districts')->get();
        return response()->json($countries);
    }
    public function getCountries(): JsonResponse
    {
        $countries = Country::all();
        return response()->json($countries);
    }
}
