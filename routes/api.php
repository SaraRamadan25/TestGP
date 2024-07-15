<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\ItemController;

use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::get('popular', [ItemController::class, 'popular']);
Route::get('items/filter', [ItemController::class, 'filterItems']);
Route::get('items/{item}', [ItemController::class, 'show']);
