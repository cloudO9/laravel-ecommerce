<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Seller\DashboardController as SellerDashboard;
use App\Http\Controllers\Buyer\DashboardController as BuyerDashboard;

/*
|--------------------------------------------------------------------------
| Public Routes (No Authentication Required)
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => view('welcome'));

// TEMPORARY TEST ROUTES - Remove these after debugging
Route::get('/test-users', function() {
    try {
        $count = App\Models\User::count();
        $users = App\Models\User::take(3)->get(['name', 'email', 'role']);
        
        $output = "Users in database: " . $count . "<br><br>";
        $output .= "Sample users:<br>";
        foreach($users as $user) {
            $output .= "- " . $user->name . " (" . $user->email . ") - Role: " . $user->role . "<br>";
        }
        
        return $output;
    } catch (Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

Route::get('/test-token', function() {
    try {
        $user = App\Models\User::first();
        
        if (!$user) {
            return 'No users found in database';
        }
        
        echo "User found: " . $user->email . "<br>";
        echo "User role: " . $user->role . "<br>";
        
        // Check if HasApiTokens trait is available
        if (!method_exists($user, 'createToken')) {
            return 'HasApiTokens trait missing from User model';
        }
        
        echo "HasApiTokens trait is present<br>";
        
        // Check current tokens count before creation
        $beforeCount = App\Models\PersonalAccessToken::count();
        echo "Tokens before creation: " . $beforeCount . "<br>";
        
        // Try to create token
        echo "Creating token...<br>";
        $token = $user->createToken('test-token');
        echo "Token object created successfully<br>";
        echo "Token ID: " . $token->accessToken->id . "<br>";
        echo "Plain text token: " . $token->plainTextToken . "<br>";
        
        // Check tokens count after creation
        $afterCount = App\Models\PersonalAccessToken::count();
        echo "Tokens after creation: " . $afterCount . "<br>";
        
        // Try to find the token in database
        $dbToken = App\Models\PersonalAccessToken::where('tokenable_id', $user->_id)->first();
        if ($dbToken) {
            echo "Token found in database: " . $dbToken->name . "<br>";
        } else {
            echo "Token NOT found in database<br>";
        }
        
        return '<br>Test completed successfully';
        
    } catch (Exception $e) {
        return 'Error: ' . $e->getMessage() . '<br>Line: ' . $e->getLine() . '<br>File: ' . $e->getFile();
    }
});

Route::get('/test-mongodb', function() {
    try {
        // Test MongoDB connection using Eloquent models
        $userCount = App\Models\User::count();
        $output = "MongoDB connection: OK<br>";
        $output .= "Users collection accessible: " . $userCount . " users found<br>";
        
        // Try to access PersonalAccessToken collection
        try {
            $tokenCount = App\Models\PersonalAccessToken::count();
            $output .= "PersonalAccessToken collection accessible: " . $tokenCount . " tokens found<br>";
        } catch (Exception $e) {
            $output .= "PersonalAccessToken collection error: " . $e->getMessage() . "<br>";
        }
        
        // Check if we can create a simple document
        try {
            $testUser = App\Models\User::first();
            if ($testUser) {
                $output .= "Sample user ID: " . $testUser->_id . "<br>";
                $output .= "Sample user email: " . $testUser->email . "<br>";
            }
        } catch (Exception $e) {
            $output .= "User access error: " . $e->getMessage() . "<br>";
        }
        
        return $output;
    } catch (Exception $e) {
        return 'MongoDB Error: ' . $e->getMessage();
    }
});

// Admin Routes (Outside of regular auth middleware)
Route::prefix('admin')->name('admin.')->group(function () {
    // Protected admin routes
        Route::get('login', App\Http\Livewire\Admin\Login::class)->name('login');

    Route::middleware(['admin.auth'])->group(function () {
        Route::get('dashboard', function() {
            return view('admin.dashboard');
        })->name('dashboard');
        
        Route::post('logout', function() {
            Auth::guard('admin')->logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
            return redirect()->route('login');
        })->name('logout');
    });
});

/*
|--------------------------------------------------------------------------
| Protected Routes (Authentication Required)
|--------------------------------------------------------------------------
*/
Route::middleware([
    'auth:sanctum', 
    config('jetstream.auth_session'), 
    'verified'
])->group(function () {
    
    // Main Dashboard Route - Redirects based on user role
    Route::get('/dashboard', function () {
        $role = auth()->user()->role;
        return redirect()->route($role . '.dashboard');
    })->name('dashboard');

    /* Seller Routes (Role-Based Protection) */
    Route::middleware('role:seller')->prefix('seller')->name('seller.')->group(function () {
        // Seller Dashboard (Post Games)
        Route::get('/dashboard', [SellerDashboard::class, 'index'])->name('dashboard');
        
        // NEW: Seller Sales & Analytics Page
        Route::get('/sales-activity', function () {
            return view('seller.sales-activity');
        })->name('sales-activity');
        
        // Manage Games
        Route::get('/manage-games', function () {
            return view('seller.manage-games');
        })->name('manage-games');
    });

    /* Buyer Routes (Role-Based Protection)*/
    Route::middleware('role:buyer')->prefix('buyer')->name('buyer.')->group(function () {
        // Buyer Dashboard
        Route::get('/dashboard', [BuyerDashboard::class, 'index'])->name('dashboard');
        
        // Browse Games - Shows all available games with search/filter
        Route::get('/browse-games', function () {
            return view('buyer.browse-games');
        })->name('browse-games');
        
        // Game Detail - Shows detailed view of a specific game
        Route::get('/game/{gameId}', function ($gameId) {
            // Validate gameId parameter
            if (!$gameId || !is_string($gameId)) {
                abort(404, 'Invalid game ID');
            }
            
            return view('buyer.game-detail', compact('gameId'));
        })->name('game-detail')->where('gameId', '[a-zA-Z0-9]+');
        
        // Search Games - Dedicated search page (optional)
        Route::get('/search', function () {
            return view('buyer.search-games');
        })->name('search-games');
        
        // Purchase History - FIXED: Changed URL to match navigation
        Route::get('/purchase-history', function () {
            return view('buyer.purchasehistory');
        })->name('purchase-history');
        
        // Cart
        Route::get('/cart', function () {
            return view('buyer.cart');
        })->name('cart');

        // Checkout
        Route::get('/checkout', function () {
            return view('buyer.checkout');
        })->name('checkout');
    });
});