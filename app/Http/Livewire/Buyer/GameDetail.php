<?php

namespace App\Http\Livewire\Buyer;

use Livewire\Component;
use App\Models\Game;
use App\Models\User;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class GameDetail extends Component
{
    public $game;
    public $seller;
    public $gameId;
    public $rentalDays = 1; // Default rental days

    public function mount($gameId)
    {
        $this->gameId = $gameId;
        $this->loadGame();
    }

    public function loadGame()
    {
        try {
            $this->game = Game::find($this->gameId);
            
            if (!$this->game) {
                session()->flash('error', 'Game not found.');
                return redirect()->route('buyer.dashboard');
            }

            // Load seller information
            if ($this->game->seller_id) {
                $this->seller = User::find($this->game->seller_id);
            }

            // Validate that the game has the correct pricing
            if (!$this->game->hasValidPrice()) {
                session()->flash('error', 'This game has invalid pricing information.');
            }

        } catch (\Exception $e) {
            session()->flash('error', 'Error loading game details.');
            return redirect()->route('buyer.dashboard');
        }
    }

    // Go back to dashboard
    public function goBack()
    {
        return redirect()->route('buyer.dashboard');
    }

    // Increase rental days
    public function increaseRentalDays()
    {
        if ($this->rentalDays < 30) { // Max 30 days
            $this->rentalDays++;
        }
    }

    // Decrease rental days
    public function decreaseRentalDays()
    {
        if ($this->rentalDays > 1) {
            $this->rentalDays--;
        }
    }

    // Add to cart function
    public function addToCart()
    {
        if (!Auth::check()) {
            session()->flash('error', 'Please login to add items to cart.');
            return;
        }

        if (!$this->game) {
            session()->flash('error', 'Game not found.');
            return;
        }

        // Check if it's the user's own game
        if ($this->game->seller_id === Auth::id()) {
            session()->flash('error', 'You cannot add your own game to cart.');
            return;
        }

        // Check if game is available
        if (!$this->game->isAvailable()) {
            session()->flash('error', 'This game is currently not available.');
            return;
        }

        try {
            $rentalDays = $this->game->is_for_rent ? $this->rentalDays : 1;
            
            Cart::addToCart(Auth::id(), $this->gameId, 1, $rentalDays);
            
            if ($this->game->is_for_rent) {
                $totalCost = $this->game->rent_price * $rentalDays;
                $message = "🛒 '{$this->game->name}' added to cart for {$rentalDays} days! Total: $" . number_format($totalCost, 2);
            } else {
                $message = "🛒 '{$this->game->name}' added to cart for $" . number_format($this->game->sell_price, 2);
            }
            
            session()->flash('message', $message);
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to add item to cart.');
        }
    }

    // Direct purchase functionality
    public function buyGame()
    {
        if (!Auth::check()) {
            session()->flash('error', 'Please login to purchase games.');
            return redirect()->route('login');
        }

        if (!$this->game) {
            session()->flash('error', 'Game not found.');
            return;
        }

        if ($this->game->is_for_rent) {
            session()->flash('error', 'This game is for rent only, not for sale.');
            return;
        }

        if (!$this->game->sell_price || $this->game->sell_price <= 0) {
            session()->flash('error', 'Invalid sell price.');
            return;
        }

        if ($this->game->seller_id === Auth::id()) {
            session()->flash('error', 'You cannot buy your own game.');
            return;
        }

        // Check if game is available
        if (!$this->game->isAvailable()) {
            session()->flash('error', 'This game is currently not available for purchase.');
            return;
        }

        try {
            // Clear current cart and add this item for direct purchase
            Cart::where('user_id', Auth::id())->delete();
            
            // Add to cart with quantity 1 (for purchase, rental_days doesn't matter)
            Cart::addToCart(Auth::id(), $this->gameId, 1, 1);
            
            // Redirect to checkout
            return redirect()->route('buyer.checkout');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to process purchase request.');
            \Log::error('Direct purchase error: ' . $e->getMessage());
        }
    }

    // Direct rental functionality
    public function rentGame()
    {
        if (!Auth::check()) {
            session()->flash('error', 'Please login to rent games.');
            return redirect()->route('login');
        }

        if (!$this->game) {
            session()->flash('error', 'Game not found.');
            return;
        }

        if (!$this->game->is_for_rent) {
            session()->flash('error', 'This game is for sale only, not for rent.');
            return;
        }

        if (!$this->game->rent_price || $this->game->rent_price <= 0) {
            session()->flash('error', 'Invalid rent price.');
            return;
        }

        if ($this->game->seller_id === Auth::id()) {
            session()->flash('error', 'You cannot rent your own game.');
            return;
        }

        // Check if game is available
        if (!$this->game->isAvailable()) {
            session()->flash('error', 'This game is currently rented by someone else.');
            return;
        }

        if ($this->rentalDays < 1 || $this->rentalDays > 30) {
            session()->flash('error', 'Rental period must be between 1 and 30 days.');
            return;
        }

        try {
            // Clear current cart and add this item for direct rental
            Cart::where('user_id', Auth::id())->delete();
            
            // Add to cart with specified rental days
            Cart::addToCart(Auth::id(), $this->gameId, 1, $this->rentalDays);
            
            // Redirect to checkout
            return redirect()->route('buyer.checkout');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to process rental request.');
            \Log::error('Direct rental error: ' . $e->getMessage());
        }
    }

    // Enhanced contact seller functionality
    public function contactSeller()
    {
        if (!$this->seller) {
            session()->flash('error', 'Seller information not available.');
            return;
        }

        if (!$this->game) {
            session()->flash('error', 'Game information not available.');
            return;
        }

        // TODO: Implement actual contact/messaging system
        // For now, show seller contact info
        $contactInfo = "📞 Contact {$this->seller->name} at: {$this->seller->email}";
        
        session()->flash('message', $contactInfo);
    }

    // Helper method to get price display
    public function getPriceDisplay()
    {
        if (!$this->game) {
            return 'N/A';
        }

        return $this->game->getFormattedPrice();
    }

    // Helper method to get type display
    public function getTypeDisplay()
    {
        if (!$this->game) {
            return 'N/A';
        }

        return $this->game->getTypeDisplay();
    }

    // Helper method to get rental total
    public function getRentalTotal()
    {
        if (!$this->game || !$this->game->is_for_rent) {
            return 0;
        }
        
        return $this->game->rent_price * $this->rentalDays;
    }

    // Check if user can interact with this game
    public function canInteractWithGame()
    {
        return Auth::check() && $this->game && $this->game->seller_id !== Auth::id();
    }

    public function render()
    {
        return view('livewire.buyer.game-detail', [
            'priceDisplay' => $this->getPriceDisplay(),
            'typeDisplay' => $this->getTypeDisplay(),
            'rentalTotal' => $this->getRentalTotal(),
            'canInteract' => $this->canInteractWithGame(),
        ]);
    }
}