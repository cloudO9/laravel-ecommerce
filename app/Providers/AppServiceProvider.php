<?php


namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Livewire\Livewire; // ✅ ADD THIS LINE
use App\Models\User;
use App\Http\Livewire\Seller\PostGame; // ✅ Also add this if not present
use Laravel\Sanctum\Sanctum; // Add this import
use App\Models\PersonalAccessToken; 


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
 public function register(): void
    {
        $this->app->singleton(StripeService::class, function ($app) {
            return new StripeService();
        });
 Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);    }

    public function boot(): void
    {
        Fortify::authenticateUsing(function ($request) {
            $user = User::where('email', $request->email)->first();
            if ($user && Hash::check($request->password, $user->password)) {
                return $user;
            }
        });

        Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
            Route::get('/dashboard', function () {
                $user = Auth::user();
                if ($user->role === 'seller') {
                    return redirect()->route('seller.dashboard');
                } else {
                    return redirect()->route('buyer.dashboard');
                }
            });
        });
             Livewire::component('seller.post-game', PostGame::class);
             Livewire::component('buyer.dashboard', \App\Http\Livewire\Buyer\Dashboard::class);
              Livewire::component('buyer.game-detail', \App\Http\Livewire\Buyer\GameDetail::class);
                Livewire::component('buyer.cart', \App\Http\Livewire\Buyer\Cart::class);
                Livewire::component('buyer.checkout',\App\Http\Livewire\Buyer\Checkout::class);
                Livewire::component('buyer.purchasehistory',\App\Http\Livewire\Buyer\PurchaseHistory::class);
                Livewire::component('seller.sales-activity', \App\Http\Livewire\Seller\SalesActivity::class);
                Livewire::component('seller.manage-games', \App\Http\Livewire\Seller\ManageGames::class);
        Livewire::component('admin.dashboard', \App\Http\Livewire\Admin\Dashboard::class);
        Livewire::component('admin.login', \App\Http\Livewire\Admin\Login::class);

                // In your routes/web.php or wherever your buyer routes are defined
    }
}
