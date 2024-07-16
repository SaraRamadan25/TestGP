<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ItemController;

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Resources\Review\ReviewCollection;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::get('popular', [ItemController::class, 'popular']);
Route::get('items/filter', [ItemController::class, 'filterItems']);
Route::get('items/{item}', [ItemController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('carts', [CartController::class, 'store']);
    Route::post('carts/{id}/add', [CartController::class, 'addItem']);
    Route::get('carts/{id}/items', [CartController::class, 'getCartItems']);
    Route::delete('carts/{id}/items/{itemId}', [CartController::class, 'removeItem']);
    Route::post('/cart/{id}/checkout', [OrderController::class, 'checkout']);
    Route::post('favorites/{item}', [ItemController::class, 'addFavorite']);
    Route::get('favorites', [ItemController::class, 'getFavorites']);
    Route::get('reviews', [ReviewController::class, 'index']);
});
