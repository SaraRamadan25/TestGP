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

// Guest Routes
Route::get('/',[HomeController::class,'index'])->name('home');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('forgot',  [AuthController::class, 'forgot'])->name('password.forgot');
Route::post('reset', [AuthController::class, 'reset'])->name('password.reset');
Route::get('/auth/redirect/github', [SocialiteAuthController::class, 'redirectToGitHub'])->middleware('web');
Route::get('/auth/callback/github', [SocialiteAuthController::class, 'handleGitHubCallback'])->middleware('web');
Route::get('/instructions', [InstructionController::class, 'index']);


// Common Routes For All ( Parent, Guard, Trainer)

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('user/{username}', [AuthController::class, 'getUserInfo']);
    Route::delete('user/{username}', [AuthController::class, 'destroy']);
    Route::get('logout', [AuthController::class, 'logout']);
    Route::post('/inquiries', [InquiryController::class,'store']);
    Route::get('/areas', [AreaController::class, 'index']);
    Route::get('/areas/{area}', [AreaController::class, 'show']);
});

// Parent Routes

Route::middleware(['auth:sanctum', 'parent'])->group(function () {
    Route::post('/health', [HealthController::class, 'store']);
    Route::get('/availableTrainers', [TrainerController::class, 'availableTrainers']);
    Route::post('/sessions/{session}/book', [SessionController::class, 'bookSession']);
    Route::get('/UserSessions/{user}', [UserController::class, 'UserSessions']);
    Route::delete('/sessions/cancel/{session}', [SessionController::class, 'CancelSession']);
    Route::get('/share-qrcode', [QrcodeController::class, 'shareQRCode'])->name('share.qrcode');
    Route::get('/check/{modelno}', [JacketController::class, 'check'])->name('check');
});

// Guard Routes

Route::middleware(['auth:sanctum', 'guard'])->group(function () {
    Route::get('/jackets/{guard:username}', [JacketController::class, 'index']);
    Route::get('/jackets/active', [JacketController::class, 'activeJackets']);

});

// Trainer Routes

Route::middleware(['auth:sanctum', 'trainer'])->group(function () {
    Route::delete('/sessions/{trainer}/{session}', [SessionController::class, 'destroy']);
    Route::post('/sessions/{trainer}', [SessionController::class, 'store']);
    Route::get('/sessions/{trainer}', [SessionController::class, 'TrainerAllSessions']);
});


Route::post('/positionstack-api',[LocationController::class, 'positionStack'])->name('location.api');
Route::post('/paypal/checkout', [PayPalTransactionController::class,'checkout']);
Route::post('/paypal/checkout/orders/{order_id}/capture', [PayPalTransactionController::class, 'completeOrder']);

