<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\GameController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\OrderController;

/*
|--------------------------------------------------------------------------
| API Routes - Simple Role-Based Protection
|--------------------------------------------------------------------------
*/

// Public routes (no authentication required)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public game routes (read-only)
Route::get('/games', [GameController::class, 'index']);
Route::get('/games/search', [GameController::class, 'search']);
Route::get('/games/{id}', [GameController::class, 'show']);
Route::get('/seller/{sellerId}/games', [GameController::class, 'sellerGames']);

// Protected routes (authentication required)
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes (available to all authenticated users)
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    /*
    |--------------------------------------------------------------------------
    | SELLER-ONLY API ROUTES
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:seller')->prefix('seller')->group(function () {
        // Game management
        Route::get('/my-games', [GameController::class, 'myGames']);
        Route::post('/games', [GameController::class, 'store']);
        Route::put('/games/{id}', [GameController::class, 'update']);
        Route::delete('/games/{id}', [GameController::class, 'destroy']);
        Route::patch('/games/{id}/status', [GameController::class, 'updateStatus']);
        
        // Sales history
        Route::get('/sales-stats', [App\Http\Controllers\API\OrderHistoryController::class, 'sellerStats']);
        Route::get('/sales-history', [App\Http\Controllers\API\OrderHistoryController::class, 'sellerOrders']);
        Route::get('/sales-games', [App\Http\Controllers\API\OrderHistoryController::class, 'sellerGames']);
    });
    
    /*
    |--------------------------------------------------------------------------
    | BUYER-ONLY API ROUTES  
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:buyer')->prefix('buyer')->group(function () {
        // Cart management
        Route::get('/cart', [CartController::class, 'index']);
        Route::post('/cart', [CartController::class, 'store']);
        Route::put('/cart/{id}', [CartController::class, 'update']);
        Route::delete('/cart/{id}', [CartController::class, 'destroy']);
        Route::delete('/cart', [CartController::class, 'clear']);
        Route::get('/cart/count', [CartController::class, 'count']);
        Route::get('/cart/checkout', [CartController::class, 'checkout']);
        
        // Orders and purchases
        Route::post('/orders/payment-intent', [OrderController::class, 'createPaymentIntent']);
        Route::post('/orders', [OrderController::class, 'processOrder']);
        Route::get('/orders', [OrderController::class, 'index']);
        Route::get('/orders/{id}', [OrderController::class, 'show']);
        Route::patch('/orders/{id}/cancel', [OrderController::class, 'cancel']);
        
        // Purchase history
        Route::get('/purchase-stats', [App\Http\Controllers\API\OrderHistoryController::class, 'buyerStats']);
        Route::get('/purchase-history', [App\Http\Controllers\API\OrderHistoryController::class, 'buyerOrders']);
    });
    
    /*
    |--------------------------------------------------------------------------
    | SHARED ROUTES (Both buyers and sellers can access)
    |--------------------------------------------------------------------------
    */
    Route::get('/order/{id}', [App\Http\Controllers\API\OrderHistoryController::class, 'show']);
});

/*
|--------------------------------------------------------------------------
| TEST ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/test-auth', function() {
    return response()->json([
        'success' => true,
        'message' => 'API is working!',
        'timestamp' => now()
    ]);
})->middleware('auth:sanctum');

Route::get('/test-buyer', function() {
    return response()->json([
        'success' => true,
        'message' => 'Buyer access confirmed!',
        'user_role' => auth()->user()->role
    ]);
})->middleware(['auth:sanctum', 'role:buyer']);

Route::get('/test-seller', function() {
    return response()->json([
        'success' => true,
        'message' => 'Seller access confirmed!',
        'user_role' => auth()->user()->role
    ]);
})->middleware(['auth:sanctum', 'role:seller']);