<?php

use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\AdminDashboardController;
use App\Http\Controllers\Api\AdminDeliveryController;
use App\Http\Controllers\Api\AdminOrderController;
use App\Http\Controllers\Api\AdminUserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CourierController;
use App\Http\Controllers\Api\MidtransCallbackController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

/*
|--------------------------------------------------------------------------
| PUBLIC PRODUCTS (UNTUK GUEST)
|--------------------------------------------------------------------------
*/

Route::get('/public/products', [ProductController::class, 'publicProducts']);
Route::get('/public/products/{product}', [ProductController::class, 'show']);

Route::post('/midtrans/notification', [MidtransCallbackController::class, 'handle']);
Route::post('/midtrans/callback', [MidtransCallbackController::class, 'handle']);

Route::post('/midtrans/test', function () {
    return response()->json(['ok' => true]);
});

/*
|--------------------------------------------------------------------------
| Customer PROTECTED (LOGIN REQUIRED)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // user
    Route::post('/logout', [AuthController::class, 'logout']); //
    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/me', [AuthController::class, 'update']); // update profile
    Route::put('/me/password', [AuthController::class, 'updatePassword']); // update password

    // products
    Route::apiResource('products', ProductController::class)->only('index', 'show');

    // address
    Route::apiResource('addresses', AddressController::class);

    // cart
    Route::get('cart', [CartController::class, 'index']);
    Route::post('cart/add', [CartController::class, 'store']);
    Route::patch('cart/{item}', [CartController::class, 'update']);
    Route::delete('cart/{item}', [CartController::class, 'destroy']);
    Route::delete('cart', [CartController::class, 'clear']);

    // orders
    Route::get('orders', [OrderController::class, 'index']);
    Route::post('orders/checkout', [OrderController::class, 'checkout']); // checkout
    Route::get('orders/{order}', [OrderController::class, 'show']);
    Route::get('orders/{order}/tracking', [OrderController::class, 'tracking']);
    // Route::post('orders/{order}/pay', [OrderController::class, 'pay']);
});

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum'])->prefix('admin')->group(function () {

    Route::get('dashboard', [AdminDashboardController::class, 'index']);

    //list order
    Route::get('orders', [AdminOrderController::class, 'index']);
    Route::get('orders/{order}', [AdminOrderController::class, 'show']);
    //product
    Route::apiResource('products', ProductController::class)
        ->only(['index', 'store', 'show', 'update', 'destroy']);
    //Admin User
    Route::apiResource('users', AdminUserController::class);
    //Assign
    Route::patch('orders/{order}/assign', [AdminOrderController::class, 'assign']);
    //Deliveries
    Route::get('deliveries', [AdminDeliveryController::class, 'index']);
    Route::get('deliveries/{order}', [AdminDeliveryController::class, 'show']);
});

/*
|--------------------------------------------------------------------------
| COURIER
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum'])->prefix('courier')->group(function () {
    Route::get('dashboard', [CourierController::class, 'dashboard']);

    Route::get('orders', [CourierController::class, 'index']);
    Route::get('orders/{order}', [CourierController::class, 'show']);

    Route::patch('orders/{order}/pickup', [CourierController::class, 'pickup']);

    Route::patch('orders/{order}/deliver', [CourierController::class, 'deliver']);

    Route::get('/history', [CourierController::class, 'history']);
});
