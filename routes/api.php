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

Route::get('/',[HomeController::class,'index'])->name('home');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('forgot',  [AuthController::class, 'forgot'])->name('password.forgot');
Route::post('reset', [AuthController::class, 'reset'])->name('password.reset');
Route::get('/auth/redirect/github', [SocialiteAuthController::class, 'redirectToGitHub'])->middleware('web');
Route::get('/auth/callback/github', [SocialiteAuthController::class, 'handleGitHubCallback'])->middleware('web');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('user/{username}', [AuthController::class, 'getUserInfo']);
    Route::delete('user/{username}', [AuthController::class, 'destroy']);
    Route::get('logout', [AuthController::class, 'logout']);
    Route::apiResource('health', HealthController::class)->except('index', 'show');
    Route::post('inquiries', [InquiryController::class,'store']);
    Route::get('jackets', [JacketController::class, 'index'])->middleware('admin');
    Route::get('jackets/{jacket}', [JacketController::class, 'show'])->middleware('parent');
    Route::get('jackets/moderate', [JacketController::class, 'moderate'])->middleware('guard');
    Route::get('jackets/{jacket}/vital-signs', [VitalSignController::class, 'show']);
});

Route::get('/getData', [SensorController::class, 'getData']);
Route::get('/heart-rate', [HeartRateController::class, 'getHeartRate']);

Route::post('/positionstack-api',[LocationController::class, 'positionStack'])->name('location.api');

Route::get('/share-qrcode', [QrcodeController::class, 'shareQRCode'])->name('share.qrcode');
Route::get('/check/{modelno}', [JacketController::class, 'check'])->name('check');

Route::get('/areas', [AreaController::class, 'index']);
Route::get('/areas/{area}', [AreaController::class, 'show']);

Route::get('/instructions', [InstructionController::class, 'index']);

