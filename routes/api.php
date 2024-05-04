<?php

use App\Http\Controllers\API\AreaController;
use App\Http\Controllers\API\HealthController;
use App\Http\Controllers\API\InquiryController;
use App\Http\Controllers\API\InstructionController;
use App\Http\Controllers\API\JacketController;
use App\Http\Controllers\API\LocationController;
use App\Http\Controllers\API\SessionController;
use App\Http\Controllers\API\TrainerController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PayPalTransactionController;
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


// Common routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('user/{username}', [AuthController::class, 'getUserInfo']);
    Route::delete('user/{username}', [AuthController::class, 'destroy']);
    Route::get('logout', [AuthController::class, 'logout']);
    Route::post('/inquiries', [InquiryController::class,'store']);
    Route::post('/sessions{session}/book', [SessionController::class, 'book']);

    Route::get('/UserSessions/{user}', [UserController::class, 'UserSessions']);

});

Route::get('trainerAllSessions', [SessionController::class, 'TrainerAllSessions']);

// Parent routes
Route::middleware(['auth:sanctum', 'parent'])->group(function () {
    Route::post('/health', [HealthController::class, 'store']);
    Route::get('/jackets', [JacketController::class, 'index']);
    Route::get('/trainers', [TrainerController::class, 'index']);
    Route::post('/sessions/{session}', [SessionController::class, 'bookSession']);
    Route::get('/sessions', [SessionController::class, 'bookedSessions']);
    Route::delete('/sessions/{session}', [SessionController::class, 'CancelSession']);
    Route::get('/availableTrainers', [TrainerController::class, 'indexUser']);

});

// Guard routes
Route::middleware(['auth:sanctum', 'guard'])->group(function () {
    Route::get('/jackets/active', [JacketController::class, 'moderate']);
});
// Trainer routes
Route::middleware(['auth:sanctum', 'trainer'])->group(function () {
    Route::post('/sessions', [SessionController::class, 'store']);
    Route::get('/allTrainerSessions', [TrainerController::class, 'indexTrainer']);
    Route::delete('/sessions/{session}', [SessionController::class, 'destroy']);
});


Route::post('/positionstack-api',[LocationController::class, 'positionStack'])->name('location.api');

Route::get('/share-qrcode', [QrcodeController::class, 'shareQRCode'])->name('share.qrcode');
Route::get('/check/{modelno}', [JacketController::class, 'check'])->name('check');

Route::get('/areas', [AreaController::class, 'index']);
Route::get('/areas/{area}', [AreaController::class, 'show']);

Route::get('/instructions', [InstructionController::class, 'index']);

Route::post('/paypal/checkout', [PayPalTransactionController::class,'checkout']);
Route::post('/paypal/checkout/orders/{order_id}/capture', [PayPalTransactionController::class, 'completeOrder']);

