<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FaceBookController;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\HeartRateController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JacketController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\QrcodeController;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\SocialiteAuthController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
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

//Protected Routes

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', [AuthController::class, 'getUserInfo']);
    Route::get('/logout', [AuthController::class, 'logout']);
});

//UnProtected Routes

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('forgot',  [AuthController::class, 'forgot'])->name('password.forgot');
Route::post('reset', [AuthController::class, 'reset'])->name('password.reset');
Route::apiResource('users', UserController::class)->except('index');


Route::get('/getData', [SensorController::class, 'getData']);

Route::post('/sensor-data', [SensorController::class, 'sensorData']);


Route::get('/heart-rate', [HeartRateController::class, 'getHeartRate']);

Route::post('/positionstack-api',[LocationController::class, 'positionStack'])->name('location.api');
Route::get('/positionstack', [LocationController::class, 'positionStack']);

Route::post('/arcgis-api',[LocationController::class, 'arcgis'])->name('weather.api');



Route::get('/',[HomeController::class,'index'])->name('home');
// Other routes
Route::get('/check/{modelno}', [JacketController::class, 'check'])->name('check');
Route::get('/share-qrcode', [QrcodeController::class, 'shareQRCode'])->name('share.qrcode');

Route::post('/scan-jacket', [JacketController::class, 'scanJacket'])->name('scan.jacket');


Route::post('/paypal/create-payment', [PayPalController::class, 'createPayment']);
Route::get('/paypal/execute-payment', [PayPalController::class, 'executePayment'])->name('paypal.execute');




Route::get('/auth/redirect/github', [SocialiteAuthController::class, 'redirectToGitHub'])->middleware('web');
Route::get('/auth/callback/github', [SocialiteAuthController::class, 'handleGitHubCallback'])->middleware('web');

Route::get('login/facebook', [FaceBookController::class, 'redirectToFacebook'])->name('login.facebook')->middleware('web');
Route::get('login/facebook/callback', [FaceBookController::class, 'handleFacebookCallback'])->middleware('web');

Route::post('submit-health-data',[HealthController::class,'submitHealthData']);
