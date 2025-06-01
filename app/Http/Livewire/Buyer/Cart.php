<?php

namespace App\Http\Livewire\Buyer;

use Livewire\Component;
use App\Models\Cart as CartModel;
use App\Models\Game;
use Illuminate\Support\Facades\Auth;

class Cart extends Component
{
    public $cartItems = [];
    public $cartCount = 0;
    public $cartTotal = 0;

    public function mount()
    {
        $this->loadCart();
    }

    // Load cart data
    public function loadCart()
    {
        if (Auth::check()) {
            $this->cartItems = CartModel::where('user_id', Auth::id())
                                      ->with('game')
                                      ->get();
            $this->cartCount = CartModel::getCountForUser(Auth::id());
            $this->cartTotal = CartModel::getTotalForUser(Auth::id());
        } else {
            $this->cartItems = collect();
            $this->cartCount = 0;
            $this->cartTotal = 0;
        }
    }

    // Add item to cart (from other pages)
    public function addToCart($gameId, $quantity = 1, $rentalDays = 1)
    {
        if (!Auth::check()) {
            session()->flash('error', 'Please login to add items to cart.');
            return;
        }

        try {
            $game = Game::find($gameId);
            if (!$game) {
                session()->flash('error', 'Game not found.');
                return;
            }

            // Check if it's the user's own game
            if ($game->seller_id === Auth::id()) {
                session()->flash('error', 'You cannot add your own game to cart.');
                return;
            }

            // Check if game is sold
            if ($game->isSold()) {
                session()->flash('error', 'This game has been sold and is no longer available.');
                return;
            }

            CartModel::addToCart(Auth::id(), $gameId, $quantity, $rentalDays);
            
            $this->loadCart();
            
            $message = $game->is_for_rent ? 
                " '{$game->name}' added to cart for {$rentalDays} days!" :
                "'{$game->name}' added to cart!";
                
            session()->flash('message', $message);
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to add item to cart.');
        }
    }

    // Remove item from cart
    public function removeFromCart($cartItemId)
    {
        try {
            $cartItem = CartModel::where('_id', $cartItemId)
                                ->where('user_id', Auth::id())
                                ->first();
            
            if ($cartItem) {
                $gameName = $cartItem->game->name ?? 'Unknown Game';
                $cartItem->delete();
                
                $this->loadCart();
                
                session()->flash('message', "'{$gameName}' removed from cart.");
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to remove item from cart.');
        }
    }

    // Update quantity
    public function updateQuantity($cartItemId, $quantity)
    {
        if ($quantity < 1) {
            $this->removeFromCart($cartItemId);
            return;
        }

        try {
            $cartItem = CartModel::where('_id', $cartItemId)
                                ->where('user_id', Auth::id())
                                ->first();
            
            if ($cartItem) {
                // Check if game is still available for quantity updates
                if ($cartItem->game && $cartItem->game->isSold()) {
                    session()->flash('error', 'This game has been sold. Please remove it from your cart.');
                    return;
                }

                $cartItem->quantity = $quantity;
                $cartItem->save();
                
                $this->loadCart();
                session()->flash('message', 'Quantity updated successfully.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update quantity.');
        }
    }

    // Update rental days
    public function updateRentalDays($cartItemId, $days)
    {
        if ($days < 1) $days = 1;
        if ($days > 30) $days = 30; // Max 30 days

        try {
            $cartItem = CartModel::where('_id', $cartItemId)
                                ->where('user_id', Auth::id())
                                ->first();
            
            if ($cartItem && $cartItem->game && $cartItem->game->is_for_rent) {
                // Check if game is still available for rental
                if ($cartItem->game->isSold()) {
                    session()->flash('error', 'This game has been sold. Please remove it from your cart.');
                    return;
                }

                $oldDays = $cartItem->rental_days;
                $cartItem->rental_days = $days;
                $cartItem->save();
                
                $this->loadCart();
                
                // Calculate new total for this item
                $newTotal = $cartItem->game->rent_price * $days;
                
                session()->flash('message', " Rental period updated to {$days} days. New total: $" . number_format($newTotal, 2));
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update rental days.');
            \Log::error('Update rental days error: ' . $e->getMessage());
        }
    }

    // NEW: Purchase individual item from cart
    public function buyIndividualItem($cartItemId)
    {
        try {
            $cartItem = CartModel::where('_id', $cartItemId)
                                ->where('user_id', Auth::id())
                                ->first();
            
            if (!$cartItem) {
                session()->flash('error', 'Cart item not found.');
                return;
            }

            $game = $cartItem->game;
            if (!$game) {
                session()->flash('error', 'Game not found.');
                return;
            }

            // Check game availability
            if ($game->isSold()) {
                session()->flash('error', 'This game has been sold and is no longer available.');
                return;
            }

            if ($game->isRented()) {
                session()->flash('error', 'This game is currently rented. Please wait until it becomes available.');
                return;
            }

            // Validate the purchase
            if ($game->is_for_rent) {
                session()->flash('error', 'This game is for rent only, not for sale.');
                return;
            }

            if ($game->seller_id === Auth::id()) {
                session()->flash('error', 'You cannot buy your own game.');
                return;
            }

            // Clear current cart and keep only this item
            CartModel::where('user_id', Auth::id())
                    ->where('_id', '!=', $cartItemId)
                    ->delete();
            
            $this->loadCart();
            
            // Redirect to checkout
            return redirect()->route('buyer.checkout');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to process individual purchase.');
            \Log::error('Individual purchase error: ' . $e->getMessage());
        }
    }

    // NEW: Rent individual item from cart
    public function rentIndividualItem($cartItemId)
    {
        try {
            $cartItem = CartModel::where('_id', $cartItemId)
                                ->where('user_id', Auth::id())
                                ->first();
            
            if (!$cartItem) {
                session()->flash('error', 'Cart item not found.');
                return;
            }

            $game = $cartItem->game;
            if (!$game) {
                session()->flash('error', 'Game not found.');
                return;
            }

            // Check game availability
            if ($game->isSold()) {
                session()->flash('error', 'This game has been sold and is no longer available.');
                return;
            }

            if ($game->isRented()) {
                session()->flash('error', 'This game is currently rented by someone else. Please wait until it becomes available.');
                return;
            }

            // Validate the rental
            if (!$game->is_for_rent) {
                session()->flash('error', 'This game is for sale only, not for rent.');
                return;
            }

            if ($game->seller_id === Auth::id()) {
                session()->flash('error', 'You cannot rent your own game.');
                return;
            }

            if ($cartItem->rental_days < 1 || $cartItem->rental_days > 30) {
                session()->flash('error', 'Invalid rental period. Must be between 1-30 days.');
                return;
            }

            // Clear current cart and keep only this item
            CartModel::where('user_id', Auth::id())
                    ->where('_id', '!=', $cartItemId)
                    ->delete();
            
            $this->loadCart();
            
            // Redirect to checkout
            return redirect()->route('buyer.checkout');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to process individual rental.');
            \Log::error('Individual rental error: ' . $e->getMessage());
        }
    }

    // Clear entire cart
    public function clearCart()
    {
        try {
            CartModel::where('user_id', Auth::id())->delete();
            $this->loadCart();
            session()->flash('message', ' Cart cleared successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to clear cart.');
        }
    }

    // Continue shopping
    public function continueShopping()
    {
        return redirect()->route('buyer.dashboard');
    }

    // UPDATED: Proceed to checkout with availability checks
    public function checkout()
    {
        if ($this->cartCount === 0) {
            session()->flash('error', 'Your cart is empty.');
            return;
        }

        // Check if any items in cart are no longer available
        $unavailableItems = [];
        foreach ($this->cartItems as $item) {
            if (!$item->game) {
                $unavailableItems[] = 'Unknown Game';
                continue;
            }

            if ($item->game->isSold()) {
                $unavailableItems[] = $item->game->name . ' (sold)';
            } elseif ($item->game->isRented() && !$item->game->is_for_rent) {
                $unavailableItems[] = $item->game->name . ' (currently rented)';
            }
        }

        if (!empty($unavailableItems)) {
            $message = 'Some items in your cart are no longer available: ' . implode(', ', $unavailableItems) . '. Please remove them and try again.';
            session()->flash('error', $message);
            return;
        }

        return redirect()->route('buyer.checkout');
    }

    // Helper method to check if item can be purchased/rented
    public function canPurchaseItem($cartItem)
    {
        if (!$cartItem->game) return false;
        
        return !$cartItem->game->isSold() && !$cartItem->game->isRented();
    }

    // Helper method to get item availability status
    public function getItemAvailabilityStatus($cartItem)
    {
        if (!$cartItem->game) return 'Game not found';
        
        if ($cartItem->game->isSold()) return 'sold';
        if ($cartItem->game->isRented()) return 'rented';
        return 'available';
    }

    public function render()
    {
        return view('livewire.buyer.cart');
    }
}