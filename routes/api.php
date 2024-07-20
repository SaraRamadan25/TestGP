<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\ItemController;

use App\Http\Controllers\NotificationController;
use App\Http\Controllers\NotificationSettingController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ShippingAddressController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::get('popular', [ItemController::class, 'popular']);
Route::get('items/filter', [ItemController::class, 'filterItems']);
Route::get('items/{item}', [ItemController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('carts', [CartController::class, 'store']);
    Route::post('carts/{cart}/add', [CartController::class, 'addItem']);
    Route::get('carts/{cart}/items', [CartController::class, 'getCartItems']);
    Route::delete('carts/{cart}/items/{item}', [CartController::class, 'removeItem']);
    Route::post('cart/{cart}/checkout', [OrderController::class, 'checkout']);
    Route::post('favorites/{item}', [ItemController::class, 'addFavorite']);
    Route::get('favorites', [ItemController::class, 'getFavorites']);
    Route::get('reviews', [ReviewController::class, 'index']);

    Route::get('user', [UserController::class, 'show']);
    Route::post('user', [UserController::class, 'update']);
    Route::get('notification-settings', [NotificationSettingController::class, 'index']);
    Route::put('notification-settings', [NotificationSettingController::class, 'update']);

    Route::get('delivered-orders', [OrderController::class, 'deliveredOrders']);

    Route::get('notifications', [NotificationController::class, 'getNotifications']);
    Route::post('notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead']);

    Route::post('/payment-methods', [PaymentMethodController::class, 'store']);

    Route::get('shipping-addresses', [ShippingAddressController::class, 'index']);
    Route::post('shipping-addresses', [ShippingAddressController::class, 'store']);
    Route::get('shipping-addresses/{shipping_address}', [ShippingAddressController::class, 'show']);
    Route::put('shipping-addresses/{shipping_address}', [ShippingAddressController::class, 'update']);

    Route::get('cities', [CityController::class, 'getCities']);
    Route::get('countries', [CountryController::class, 'getCountries']);
    Route::get('districts', [DistrictController::class, 'getAllDistricts']);
    Route::get('districts/{city}', [DistrictController::class, 'getDistricts']);
});
