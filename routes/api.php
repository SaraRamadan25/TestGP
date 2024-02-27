<?php

use App\Http\Controllers\API\AreaController;
use App\Http\Controllers\API\HealthController;
use App\Http\Controllers\API\HeartRateController;
use App\Http\Controllers\API\InquiryController;
use App\Http\Controllers\API\InstructionController;
use App\Http\Controllers\API\JacketController;
use App\Http\Controllers\API\LocationController;
use App\Http\Controllers\API\SensorController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\VitalSignController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QrcodeController;
use App\Http\Controllers\SocialiteAuthController;
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

//Protected Routes - Auth Part

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user/{username}', [AuthController::class, 'getUserInfo']);
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::apiResource('health', HealthController::class)->except('index', 'show','update');

});


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('forgot',  [AuthController::class, 'forgot'])->name('password.forgot');
Route::post('reset', [AuthController::class, 'reset'])->name('password.reset');
Route::apiResource('users', UserController::class)->except('index');

// Login with 3rd part

Route::get('/auth/redirect/github', [SocialiteAuthController::class, 'redirectToGitHub'])->middleware('web');
Route::get('/auth/callback/github', [SocialiteAuthController::class, 'handleGitHubCallback'])->middleware('web');

Route::get('/',[HomeController::class,'index'])->name('home');


Route::get('/getData', [SensorController::class, 'getData']);

Route::post('/sensor-data', [SensorController::class, 'sensorData']);


Route::get('/heart-rate', [HeartRateController::class, 'getHeartRate']);

Route::post('/positionstack-api',[LocationController::class, 'positionStack'])->name('location.api');


Route::get('/check/{modelno}', [JacketController::class, 'check'])->name('check');
Route::get('/share-qrcode', [QrcodeController::class, 'shareQRCode'])->name('share.qrcode');

Route::post('/scan-jacket', [JacketController::class, 'scanJacket'])->name('scan.jacket');




Route::apiResource('inquiries', InquiryController::class)->only('store', 'show');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/jackets', [JacketController::class, 'index'])->middleware('admin');
    Route::get('/jackets/{jacket}', [JacketController::class, 'show'])->middleware('parent');
    Route::get('/jackets/moderate', [JacketController::class, 'moderate'])->middleware('guard');
});

Route::get('/areas', [AreaController::class, 'index']);
Route::get('/areas/{area}', [AreaController::class, 'show']);

Route::post('/inquiries', [InquiryController::class, 'store']);
Route::get('/inquiries/{inquiry}', [InquiryController::class, 'show']);
Route::delete('/inquiries/{inquiry}', [InquiryController::class, 'destroy']);

Route::get('/instructions', [InstructionController::class, 'index']);

Route::get('/users/{user}', [UserController::class, 'show']);
Route::delete('/users/{user}', [UserController::class, 'destroy']);

Route::get('/jackets/{jacket_id}/vital-signs', [VitalSignController::class, 'show']);
Route::put('/jackets/{jacket_id}/vital-signs', [VitalSignController::class, 'update']);
