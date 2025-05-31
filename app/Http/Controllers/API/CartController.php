<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    /**
     * Get user's cart items
     */
    public function index()
    {
        try {
            $cartItems = Cart::where('user_id', Auth::id())
                           ->with('game')
                           ->get();

            // Add formatted prices using your existing methods
            $cartItems->transform(function ($item) {
                $item->total_price = $item->getTotalPrice();
                $item->formatted_total_price = $item->getFormattedTotalPrice();
                return $item;
            });

            // Calculate totals using your existing methods
            $total = Cart::getTotalForUser(Auth::id());
            $count = Cart::getCountForUser(Auth::id());

            return response()->json([
                'success' => true,
                'cart_items' => $cartItems,
                'total' => $total,
                'formatted_total' => '$' . number_format($total, 2),
                'count' => $count
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add item to cart
     */
    public function store(Request $request)
    {
        // Validate input (same as GameController pattern)
        $validator = Validator::make($request->all(), [
            'game_id' => 'required|string',
            'quantity' => 'integer|min:1|max:10',
            'rental_days' => 'integer|min:1|max:30'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $game = Game::find($request->game_id);

            if (!$game) {
                return response()->json([
                    'success' => false,
                    'message' => 'Game not found'
                ], 404);
            }

            // Same validation as your Livewire components
            if ($game->seller_id === Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot add your own game to cart'
                ], 422);
            }

            // Check if game is sold (if method exists)
            if (method_exists($game, 'isSold') && $game->isSold()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This game has been sold and is no longer available'
                ], 422);
            }

            // Use your existing Cart::addToCart method
            $cartItem = Cart::addToCart(
                Auth::id(),
                $request->game_id,
                $request->quantity ?? 1,
                $request->rental_days ?? 1
            );

            // Load the game relationship
            $cartItem->load('game');

            // Same message format as your Livewire
            $message = $game->is_for_rent ? 
                "'{$game->name}' added to cart for {$cartItem->rental_days} days!" :
                "'{$game->name}' added to cart!";

            return response()->json([
                'success' => true,
                'message' => $message,
                'cart_item' => $cartItem,
                'cart_count' => Cart::getCountForUser(Auth::id())
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add item to cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update cart item quantity or rental days
     */
    public function update(Request $request, $cartItemId)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'sometimes|integer|min:1|max:10',
            'rental_days' => 'sometimes|integer|min:1|max:30'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Same pattern as your Livewire components
            $cartItem = Cart::where('_id', $cartItemId)
                          ->where('user_id', Auth::id())
                          ->first();

            if (!$cartItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart item not found'
                ], 404);
            }

            // Load game to check availability
            $cartItem->load('game');

            if ($cartItem->game && method_exists($cartItem->game, 'isSold') && $cartItem->game->isSold()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This game has been sold. Please remove it from your cart.'
                ], 422);
            }

            // Update fields
            if ($request->has('quantity')) {
                $cartItem->quantity = $request->quantity;
            }

            if ($request->has('rental_days')) {
                $cartItem->rental_days = $request->rental_days;
            }

            $cartItem->save();

            return response()->json([
                'success' => true,
                'message' => 'Cart item updated successfully',
                'cart_item' => $cartItem,
                'new_total_price' => $cartItem->getTotalPrice(),
                'formatted_total_price' => $cartItem->getFormattedTotalPrice()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update cart item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove item from cart
     */
    public function destroy($cartItemId)
    {
        try {
            // Same pattern as your Livewire removeFromCart method
            $cartItem = Cart::where('_id', $cartItemId)
                          ->where('user_id', Auth::id())
                          ->first();

            if (!$cartItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart item not found'
                ], 404);
            }

            // Get game name before deletion (same as your Livewire)
            $gameName = $cartItem->game->name ?? 'Unknown Game';
            $cartItem->delete();

            return response()->json([
                'success' => true,
                'message' => "'{$gameName}' removed from cart successfully",
                'cart_count' => Cart::getCountForUser(Auth::id())
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove cart item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear entire cart
     */
    public function clear()
    {
        try {
            // Same as your Livewire clearCart method
            Cart::where('user_id', Auth::id())->delete();

            return response()->json([
                'success' => true,
                'message' => 'Cart cleared successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get cart count (for badge/notification)
     */
    public function count()
    {
        try {
            $count = Cart::getCountForUser(Auth::id());

            return response()->json([
                'success' => true,
                'count' => $count
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get cart count',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Prepare for checkout - validate cart and return summary
     */
    public function checkout()
    {
        try {
            $cartItems = Cart::where('user_id', Auth::id())
                           ->with('game')
                           ->get();

            if ($cartItems->count() === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your cart is empty'
                ], 422);
            }

            // Check availability (same logic as your Livewire checkout method)
            $unavailableItems = [];
            $validItems = [];

            foreach ($cartItems as $item) {
                if (!$item->game) {
                    $unavailableItems[] = 'Unknown Game';
                    continue;
                }

                if (method_exists($item->game, 'isSold') && $item->game->isSold()) {
                    $unavailableItems[] = $item->game->name . ' (sold)';
                } elseif (method_exists($item->game, 'isRented') && $item->game->isRented() && !$item->game->is_for_rent) {
                    $unavailableItems[] = $item->game->name . ' (currently rented)';
                } else {
                    $validItems[] = $item;
                }
            }

            if (!empty($unavailableItems)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Some items in your cart are no longer available: ' . implode(', ', $unavailableItems) . '. Please remove them and try again.',
                    'unavailable_items' => $unavailableItems
                ], 422);
            }

            // Calculate totals using your existing method
            $total = Cart::getTotalForUser(Auth::id());

            return response()->json([
                'success' => true,
                'message' => 'Cart is valid for checkout',
                'cart_items' => $validItems,
                'total' => $total,
                'formatted_total' => '$' . number_format($total, 2),
                'item_count' => count($validItems)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to validate cart for checkout',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}