<?php

use App\Http\Controllers\API\AreaController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\HealthController;
use App\Http\Controllers\API\InquiryController;
use App\Http\Controllers\API\InstructionController;
use App\Http\Controllers\API\JacketController;
use App\Http\Controllers\API\LocationController;
use App\Http\Controllers\API\QrcodeController;
use App\Http\Controllers\API\SessionController;
use App\Http\Controllers\API\TrainerController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\PayPalTransactionController;
use App\Http\Controllers\SocialiteAuthController;
use Illuminate\Support\Facades\Route;

// Guest Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('forgot',  [AuthController::class, 'forgot'])->name('password.forgot');
Route::post('reset', [AuthController::class, 'reset'])->name('password.reset');
Route::get('/auth/redirect/github', [SocialiteAuthController::class, 'redirectToGitHub'])->middleware('web');
Route::get('/auth/callback/github', [SocialiteAuthController::class, 'handleGitHubCallback'])->middleware('web');
Route::get('/instructions', [InstructionController::class, 'index']);

// Common Routes For All ( Parent, Guard, Trainer)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('user/{user:username}', [AuthController::class, 'getUserInfo']);
    Route::delete('user/{user:username}', [AuthController::class, 'destroy']);
    Route::get('logout', [AuthController::class, 'logout']);
    Route::post('/inquiries', [InquiryController::class,'store']);
    Route::get('/areas', [AreaController::class, 'index']);
    Route::get('/areas/{area:name}', [AreaController::class, 'show']);
});

// Parent Routes
Route::middleware(['auth:sanctum', 'parent'])->group(function () {
    Route::post('/health', [HealthController::class, 'store']);
    Route::get('/availableTrainers', [TrainerController::class, 'availableTrainers']);
    Route::post('/sessions/{session}/book', [SessionController::class, 'bookSession']);
    Route::get('/UserSessions/{user:username}', [UserController::class, 'UserSessions']);
    Route::delete('/sessions/cancel/{session}', [SessionController::class, 'CancelSession']);
    Route::get('/share-qrcode', [QrcodeController::class, 'shareQRCode'])->name('share.qrcode');
    Route::get('/check/{modelno}', [JacketController::class, 'check'])->name('check');
});

// Guard Routes
Route::middleware(['auth:sanctum', 'guard'])->group(function () {
    Route::get('/jackets/{guard:username}', [JacketController::class, 'index']);
    Route::get('/jackets/active/{guard:username}', [JacketController::class, 'activeJackets']);

});

// Trainer Routes
Route::middleware(['auth:sanctum', 'trainer'])->group(function () {
    Route::get('/sessions/{trainer:username}', [SessionController::class, 'TrainerAllSessions']);
    Route::post('/sessions/{trainer:username}', [SessionController::class, 'store']);
    Route::delete('/sessions/{trainer:username}/{session}', [SessionController::class, 'destroy']);
});

// wait for flutter implementation
Route::post('/positionstack-api',[LocationController::class, 'positionStack'])->name('location.api');
Route::post('/paypal/checkout', [PayPalTransactionController::class,'checkout']);
Route::post('/paypal/checkout/orders/{order_id}/capture', [PayPalTransactionController::class, 'completeOrder']);

