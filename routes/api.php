<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PosApiController;

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Protected routes (requires token)
Route::middleware('auth:sanctum')->group(function () {
    // Auth endpoints
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // POS endpoints
    Route::get('/settings', [PosApiController::class, 'getSettings']);
    Route::get('/categories', [PosApiController::class, 'getCategories']);
    Route::get('/products', [PosApiController::class, 'getProducts']);
    Route::post('/checkout', [PosApiController::class, 'checkout']);

    // Dashboard endpoints
    Route::get('/dashboard', [\App\Http\Controllers\Api\DashboardApiController::class, 'getStats']);

    // Admin endpoints
    Route::get('/admin/users', [\App\Http\Controllers\Api\AdminApiController::class, 'getUsers']);
    Route::delete('/admin/users/{id}', [\App\Http\Controllers\Api\AdminApiController::class, 'deleteUser']);

    // Order endpoints (Riwayat Transaksi)
    Route::get('/orders', [\App\Http\Controllers\Api\OrderApiController::class, 'getOrders']);

    // Kitchen endpoints (Dapur)
    Route::get('/kitchen/orders', [\App\Http\Controllers\Api\OrderApiController::class, 'getKitchenOrders']);
    Route::post('/kitchen/orders/{id}/status', [\App\Http\Controllers\Api\OrderApiController::class, 'updateStatus']);
});
