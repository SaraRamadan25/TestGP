<?php

use App\Http\Controllers\FaceBookController;
use App\Http\Controllers\HeartRateController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\QrcodeController;
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


Route::get('/',[HomeController::class,'index'])->name('home');
// Other routes
Route::get('/generate-qrcode/{id}', [QrcodeController::class, 'generateQrCode'])->name('generate.qrcode');
Route::get('/heart-rate', [HeartRateController::class, 'getHeartRate']);
Route::post('/positionstack-api',[LocationController::class, 'positionStack']);
Route::get('/positionstack', [LocationController::class, 'positionStack']);
Route::post('/arcgis-api',[LocationController::class, 'arcgis']);

Route::post('/paypal/create-payment', [PayPalController::class, 'createPayment']);
Route::post('/paypal/execute-payment', [PayPalController::class, 'executePayment']);
Route::get('login/facebook', [FaceBookController::class, 'redirectToFacebook'])->name('login.facebook')->middleware('web');
Route::get('login/facebook/callback', [FaceBookController::class, 'handleFacebookCallback'])->middleware('web');
