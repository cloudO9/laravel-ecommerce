<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Game;
use App\Models\Order;
use App\Models\GameStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class OrderController extends Controller
{
    public function __construct()
    {
        // Set Stripe API key
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Create payment intent for checkout
     */
    public function createPaymentIntent(Request $request)
    {
        try {
            $userId = Auth::id();
            
            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $cartItems = Cart::where('user_id', $userId)->with('game')->get();

            if ($cartItems->count() === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your cart is empty'
                ], 422);
            }

            // Calculate total using your existing Cart model method
            $total = Cart::getTotalForUser($userId);

            if ($total <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid cart total',
                    'debug' => [
                        'cart_items_count' => $cartItems->count(),
                        'calculated_total' => $total
                    ]
                ], 422);
            }

            // Determine order type
            $orderType = $this->determineOrderType($cartItems);

            // Create Stripe payment intent
            $paymentIntent = PaymentIntent::create([
                'amount' => round($total * 100), // Convert to cents
                'currency' => 'usd',
                'metadata' => [
                    'user_id' => $userId,
                    'order_type' => $orderType,
                    'items_count' => $cartItems->count()
                ],
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            return response()->json([
                'success' => true,
                'client_secret' => $paymentIntent->client_secret,
                'payment_intent_id' => $paymentIntent->id,
                'amount' => $total,
                'order_type' => $orderType,
                'items_count' => $cartItems->count()
            ]);

        } catch (\Stripe\Exception\InvalidRequestException $e) {
            Log::error('Stripe Invalid Request: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Stripe configuration error',
                'error' => $e->getMessage()
            ], 400);
        } catch (\Stripe\Exception\AuthenticationException $e) {
            Log::error('Stripe Authentication Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Stripe authentication failed',
                'error' => 'Invalid API key'
            ], 400);
        } catch (\Exception $e) {
            Log::error('Payment intent creation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create payment intent',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process order after payment confirmation
     */
    public function processOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_intent_id' => 'required|string',
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip_code' => 'required|string|max:10',
            'country' => 'required|string|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $userId = Auth::id();

            // Verify payment with Stripe
            $paymentIntent = PaymentIntent::retrieve($request->payment_intent_id);

            if ($paymentIntent->status !== 'succeeded') {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment not completed. Status: ' . $paymentIntent->status
                ], 422);
            }

            // Get cart items
            $cartItems = Cart::where('user_id', $userId)->with('game')->get();

            if ($cartItems->count() === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your cart is empty'
                ], 422);
            }

            // Validate cart items are still available
            $validation = $this->validateCartItems($cartItems);
            if (!$validation['success']) {
                return response()->json($validation, 422);
            }

            // Calculate totals
            $total = Cart::getTotalForUser($userId);
            $orderType = $this->determineOrderType($cartItems);

            // Create order
            $order = Order::create([
                'user_id' => $userId,
                'order_number' => Order::generateOrderNumber(),
                'type' => $orderType,
                'status' => 'paid',
                'items' => $this->prepareOrderItems($cartItems),
                'shipping_address' => [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'address' => $request->address,
                    'city' => $request->city,
                    'state' => $request->state,
                    'zip_code' => $request->zip_code,
                    'country' => $request->country,
                ],
                'contact_info' => [
                    'email' => $request->email,
                    'phone' => $request->phone,
                ],
                'payment_info' => [
                    'method' => 'stripe',
                    'payment_intent_id' => $request->payment_intent_id,
                ],
                'subtotal' => $total,
                'total' => $total,
                'stripe_payment_intent_id' => $request->payment_intent_id,
            ]);

            // Update game statuses
            $this->updateGameStatuses($cartItems, $order);

            // Clear cart
            Cart::where('user_id', $userId)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully!',
                'order' => [
                    'order_id' => $order->_id,
                    'order_number' => $order->order_number,
                    'total' => $order->total,
                    'status' => $order->status,
                    'type' => $order->type
                ]
            ], 201);

        } catch (\Stripe\Exception\InvalidRequestException $e) {
            Log::error('Stripe error during order processing: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Payment verification failed',
                'error' => $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            Log::error('Order processing failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to process order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's orders
     */
    public function index()
    {
        try {
            $orders = Order::where('user_id', Auth::id())
                          ->orderBy('created_at', 'desc')
                          ->paginate(10);

            return response()->json([
                'success' => true,
                'orders' => $orders
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve orders',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get specific order
     */
    public function show($orderId)
    {
        try {
            $order = Order::where('_id', $orderId)
                         ->where('user_id', Auth::id())
                         ->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'order' => $order
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel order (if not shipped)
     */
    public function cancel($orderId)
    {
        try {
            $order = Order::where('_id', $orderId)
                         ->where('user_id', Auth::id())
                         ->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found'
                ], 404);
            }

            if (in_array($order->status, ['shipped', 'delivered', 'cancelled'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order cannot be cancelled'
                ], 422);
            }

            $order->update(['status' => 'cancelled']);

            // Update game statuses back to available
            if (isset($order->items) && is_array($order->items)) {
                foreach ($order->items as $item) {
                    if (isset($item['game_id'])) {
                        GameStatus::where('game_id', $item['game_id'])->delete();
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Order cancelled successfully',
                'order' => $order
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Helper methods
    private function determineOrderType($cartItems)
    {
        $hasRental = false;
        $hasPurchase = false;

        foreach ($cartItems as $item) {
            if ($item->game && $item->game->is_for_rent) {
                $hasRental = true;
            } else {
                $hasPurchase = true;
            }
        }

        if ($hasRental && $hasPurchase) {
            return 'mixed';
        } elseif ($hasRental) {
            return 'rental';
        } else {
            return 'purchase';
        }
    }

    private function validateCartItems($cartItems)
    {
        $unavailableItems = [];

        foreach ($cartItems as $item) {
            if (!$item->game) {
                $unavailableItems[] = 'Game not found (ID: ' . $item->game_id . ')';
                continue;
            }

            // Check if methods exist before calling them
            if (method_exists($item->game, 'isSold') && $item->game->isSold()) {
                $unavailableItems[] = $item->game->name . ' (sold)';
            } elseif (method_exists($item->game, 'isRented') && $item->game->isRented() && !$item->game->is_for_rent) {
                $unavailableItems[] = $item->game->name . ' (rented)';
            }
        }

        if (!empty($unavailableItems)) {
            return [
                'success' => false,
                'message' => 'Some items are no longer available: ' . implode(', ', $unavailableItems),
                'unavailable_items' => $unavailableItems
            ];
        }

        return ['success' => true];
    }

    private function prepareOrderItems($cartItems)
    {
        $items = [];
        foreach ($cartItems as $item) {
            if ($item->game) {
                $items[] = [
                    'game_id' => $item->game_id,
                    'game_name' => $item->game->name,
                    'game_image' => $item->game->image ?? null,
                    'seller_id' => $item->game->seller_id,
                    'type' => $item->game->is_for_rent ? 'rental' : 'purchase',
                    'quantity' => $item->quantity,
                    'rental_days' => $item->rental_days ?? 0,
                    'unit_price' => $item->game->is_for_rent ? $item->game->rent_price : $item->game->sell_price,
                    'total_price' => $item->getTotalPrice(),
                ];
            }
        }
        return $items;
    }

    private function updateGameStatuses($cartItems, $order)
    {
        foreach ($cartItems as $item) {
            if ($item->game) {
                if ($item->game->is_for_rent) {
                    // Mark as rented
                    GameStatus::updateOrCreate(
                        ['game_id' => $item->game_id],
                        [
                            'status' => 'rented',
                            'rented_to_user_id' => Auth::id(),
                            'rental_start_date' => now(),
                            'rental_end_date' => now()->addDays($item->rental_days ?? 1),
                            'order_id' => $order->_id,
                        ]
                    );
                } else {
                    // Mark as sold
                    GameStatus::updateOrCreate(
                        ['game_id' => $item->game_id],
                        [
                            'status' => 'sold',
                            'order_id' => $order->_id,
                        ]
                    );
                }
            }
        }
    }
}