<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\GameController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\OrderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes (no authentication required)
Route::post('/login', [AuthController::class, 'login']);

// Public game routes (read-only)
Route::get('/games', [GameController::class, 'index']);
Route::get('/games/search', [GameController::class, 'search']);
Route::get('/games/{id}', [GameController::class, 'show']);
Route::get('/seller/{sellerId}/games', [GameController::class, 'sellerGames']);

// Protected routes (authentication required - Sanctum only)
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // Game management routes (protected by Sanctum only)
    Route::get('/my-games', [GameController::class, 'myGames']);
    Route::post('/games', [GameController::class, 'store']);
    Route::put('/games/{id}', [GameController::class, 'update']);
    Route::delete('/games/{id}', [GameController::class, 'destroy']);
    Route::patch('/games/{id}/status', [GameController::class, 'updateStatus']);
    
    // Cart API routes
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart', [CartController::class, 'store']);
    Route::put('/cart/{id}', [CartController::class, 'update']);
    Route::delete('/cart/{id}', [CartController::class, 'destroy']);
    Route::delete('/cart', [CartController::class, 'clear']);
    Route::get('/cart/count', [CartController::class, 'count']);
    Route::get('/cart/validate', [CartController::class, 'validate']);
    Route::get('/cart/checkout', [CartController::class, 'checkout']);
    
    // Order/Purchase API routes (NOW PROPERLY PROTECTED)
    Route::post('/orders/payment-intent', [OrderController::class, 'createPaymentIntent']);
    Route::post('/orders', [OrderController::class, 'processOrder']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::patch('/orders/{id}/cancel', [OrderController::class, 'cancel']);
    
    // Order History API routes (Unified for Buyers & Sellers)
    Route::prefix('order-history')->group(function () {
        // Buyer endpoints
        Route::get('/buyer/stats', [App\Http\Controllers\API\OrderHistoryController::class, 'buyerStats']);
        Route::get('/buyer/orders', [App\Http\Controllers\API\OrderHistoryController::class, 'buyerOrders']);
        
        // Seller endpoints
        Route::get('/seller/stats', [App\Http\Controllers\API\OrderHistoryController::class, 'sellerStats']);
        Route::get('/seller/orders', [App\Http\Controllers\API\OrderHistoryController::class, 'sellerOrders']);
        Route::get('/seller/games', [App\Http\Controllers\API\OrderHistoryController::class, 'sellerGames']);
        
        // Shared endpoints
        Route::get('/order/{id}', [App\Http\Controllers\API\OrderHistoryController::class, 'show']);
        Route::patch('/order/{id}/cancel', [App\Http\Controllers\API\OrderHistoryController::class, 'cancel']);
    });
});

// Debug routes (remove these in production)
Route::get('/test-token-creation', function() {
    try {
        $user = App\Models\User::first();
        
        if (!$user) {
            return response()->json(['error' => 'No users found']);
        }
        
        // Check tokens before creation
        $beforeCount = App\Models\PersonalAccessToken::count();
        
        // Try to create token
        $token = $user->createToken('test-token');
        
        // Check tokens after creation
        $afterCount = App\Models\PersonalAccessToken::count();
        
        // Check if token exists in database
        $dbToken = App\Models\PersonalAccessToken::where('tokenable_id', $user->_id)->first();
        
        return response()->json([
            'success' => true,
            'user_email' => $user->email,
            'user_id' => $user->_id,
            'tokens_before' => $beforeCount,
            'tokens_after' => $afterCount,
            'token_created' => $token ? 'Yes' : 'No',
            'token_plain' => $token ? $token->plainTextToken : 'None',
            'token_in_db' => $dbToken ? 'Found' : 'Not found',
            'token_id' => $token ? $token->accessToken->id : 'None'
        ]);
        
    } catch (Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => basename($e->getFile()),
            'trace' => $e->getTraceAsString()
        ]);
    }
});

Route::get('/debug-token-save', function() {
    try {
        $user = App\Models\User::first();
        
        // Monitor the save process step by step
        $beforeCount = App\Models\PersonalAccessToken::count();
        
        // Create token manually to see each step
        $tokenName = 'debug-save-test';
        $plainTextToken = \Illuminate\Support\Str::random(40);
        
        // Create the PersonalAccessToken instance
        $accessToken = new App\Models\PersonalAccessToken([
            'name' => $tokenName,
            'token' => hash('sha256', $plainTextToken),
            'abilities' => ['*'],
            'tokenable_type' => get_class($user),
            'tokenable_id' => $user->_id,
        ]);
        
        // Check before save
        $existsBefore = $accessToken->exists;
        
        // Try to save
        $saveResult = $accessToken->save();
        
        // Check after save
        $existsAfter = $accessToken->exists;
        $afterCount = App\Models\PersonalAccessToken::count();
        
        // Try to find it in database
        $foundInDb = App\Models\PersonalAccessToken::where('token', hash('sha256', $plainTextToken))->first();
        
        return response()->json([
            'tokens_before' => $beforeCount,
            'tokens_after' => $afterCount,
            'exists_before_save' => $existsBefore,
            'exists_after_save' => $existsAfter,
            'save_result' => $saveResult,
            'token_attributes' => $accessToken->getAttributes(),
            'token_connection' => $accessToken->getConnectionName(),
            'found_in_db' => $foundInDb ? 'Yes' : 'No',
            'plain_token' => $plainTextToken,
            'hashed_token' => hash('sha256', $plainTextToken)
        ]);
        
    } catch (Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => basename($e->getFile()),
            'trace' => explode("\n", $e->getTraceAsString())
        ]);
    }
});

// Test route to verify authentication works
Route::middleware('auth:sanctum')->get('/test-auth', function() {
    return response()->json([
        'success' => true,
        'user' => auth()->user()->email,
        'user_id' => auth()->id(),
        'message' => 'Authentication works perfectly!'
    ]);
});