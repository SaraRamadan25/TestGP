<?php

use App\Http\Controllers\API\HealthController;
use App\Http\Controllers\API\HeartRateController;
use App\Http\Controllers\API\JacketController;
use App\Http\Controllers\API\LocationController;
use App\Http\Controllers\API\SensorController;
use App\Http\Controllers\API\SocialiteAuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FaceBookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QrcodeController;
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
});


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('forgot',  [AuthController::class, 'forgot'])->name('password.forgot');
Route::post('reset', [AuthController::class, 'reset'])->name('password.reset');
Route::apiResource('users', UserController::class)->except('index');

Route::get('/',[HomeController::class,'index'])->name('home');


Route::get('/getData', [SensorController::class, 'getData']);

Route::post('/sensor-data', [SensorController::class, 'sensorData']);


Route::get('/heart-rate', [HeartRateController::class, 'getHeartRate']);

Route::post('/positionstack-api',[LocationController::class, 'positionStack'])->name('location.api');
Route::get('/positionstack', [LocationController::class, 'positionStack']);

Route::post('/arcgis-api',[LocationController::class, 'arcgis'])->name('weather.api');


Route::get('/check/{modelno}', [JacketController::class, 'check'])->name('check');
Route::get('/share-qrcode', [QrcodeController::class, 'shareQRCode'])->name('share.qrcode');

Route::post('/scan-jacket', [JacketController::class, 'scanJacket'])->name('scan.jacket');

Route::get('/auth/redirect/github', [SocialiteAuthController::class, 'redirectToGitHub'])->middleware('web');
Route::get('/auth/callback/github', [SocialiteAuthController::class, 'handleGitHubCallback'])->middleware('web');

Route::get('login/facebook', [FaceBookController::class, 'redirectToFacebook'])->name('login.facebook')->middleware('web');
Route::get('login/facebook/callback', [FaceBookController::class, 'handleFacebookCallback'])->middleware('web');

Route::post('submit-health-data',[HealthController::class,'submitHealthData']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/manage', [JacketController::class, 'manage'])->middleware('admin');
    Route::get('/view', [JacketController::class, 'view'])->middleware('parent');
    Route::get('/moderate', [JacketController::class, 'moderate'])->middleware('guard');
});
