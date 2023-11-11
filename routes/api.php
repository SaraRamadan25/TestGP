<?php

use App\Http\Controllers\HeartRateController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PayPalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/heart-rate', [HeartRateController::class, 'getHeartRate']);
Route::post('/positionstack-api',[LocationController::class, 'positionStack']);
Route::post('/arcgis-api',[LocationController::class, 'arcgis']);

Route::post('/paypal/create-payment', [PayPalController::class, 'createPayment']);
Route::post('/paypal/execute-payment', [PayPalController::class, 'executePayment']);
