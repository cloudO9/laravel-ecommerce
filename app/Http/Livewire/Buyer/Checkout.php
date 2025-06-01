<?php

namespace App\Http\Livewire\Buyer;

use Livewire\Component;
use App\Models\Cart as CartModel;
use App\Models\Game;
use App\Models\Order;
use App\Models\GameStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class Checkout extends Component
{
    // Order Info
    public $cartItems = [];
    public $cartTotal = 0;
    public $orderType = 'mixed';

    // Customer Info
    public $firstName = '';
    public $lastName = '';
    public $email = '';
    public $phone = '';

    // Shipping Address
    public $address = '';
    public $city = '';
    public $state = '';
    public $zipCode = '';
    public $country = 'United States';

    // Payment
    public $stripeClientSecret = '';
    public $paymentIntentId = '';

    // UI State
    public $currentStep = 1;
    public $isProcessing = false;

    protected $rules = [
        'firstName' => 'required|string|min:2|max:50',
        'lastName' => 'required|string|min:2|max:50',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|min:10|max:20',
        'address' => 'required|string|min:10|max:255',
        'city' => 'required|string|min:2|max:100',
        'state' => 'required|string|min:2|max:100',
        'zipCode' => 'required|string|min:5|max:10',
        'country' => 'required|string|max:100',
    ];

    public function mount()
    {
        try {
            if (!Auth::check()) {
                return redirect()->route('login');
            }

            $this->loadCart();
            $this->prefillUserInfo();
            
            if (empty($this->cartItems)) {
                session()->flash('error', 'Your cart is empty.');
                return redirect()->route('buyer.cart');
            }

            $this->determineOrderType();
            
            \Log::info('Checkout mounted successfully', [
                'user_id' => Auth::id(),
                'cart_items_count' => count($this->cartItems),
                'cart_total' => $this->cartTotal,
                'order_type' => $this->orderType
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Checkout mount error: ' . $e->getMessage());
            session()->flash('error', 'Failed to load checkout page: ' . $e->getMessage());
        }
    }

    public function loadCart()
    {
        try {
            $cartItems = CartModel::where('user_id', Auth::id())->with('game')->get();
            $this->cartItems = $cartItems->toArray();
            $this->cartTotal = CartModel::getTotalForUser(Auth::id());
            
            \Log::info('Cart loaded', [
                'items_count' => count($this->cartItems),
                'total' => $this->cartTotal
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Load cart error: ' . $e->getMessage());
            $this->cartItems = [];
            $this->cartTotal = 0;
        }
    }

    public function prefillUserInfo()
    {
        try {
            $user = Auth::user();
            $this->email = $user->email ?? '';
            $names = explode(' ', $user->name ?? '', 2);
            $this->firstName = $names[0] ?? '';
            $this->lastName = $names[1] ?? '';
            
            \Log::info('User info prefilled', [
                'firstName' => $this->firstName,
                'lastName' => $this->lastName,
                'email' => $this->email
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Prefill user info error: ' . $e->getMessage());
        }
    }

    public function determineOrderType()
    {
        try {
            $hasRental = false;
            $hasPurchase = false;

            foreach ($this->cartItems as $item) {
                $game = Game::find($item['game_id']);
                if ($game && $game->is_for_rent) {
                    $hasRental = true;
                } else {
                    $hasPurchase = true;
                }
            }

            if ($hasRental && $hasPurchase) {
                $this->orderType = 'mixed';
            } elseif ($hasRental) {
                $this->orderType = 'rental';
            } else {
                $this->orderType = 'purchase';
            }
            
            \Log::info('Order type determined: ' . $this->orderType);
            
        } catch (\Exception $e) {
            \Log::error('Determine order type error: ' . $e->getMessage());
            $this->orderType = 'mixed';
        }
    }

    // FIXED nextStep method
    public function nextStep()
    {
        try {
            \Log::info('=== nextStep CALLED ===', [
                'current_step' => $this->currentStep,
                'cart_total' => $this->cartTotal
            ]);

            // Step 1 validation
            if ($this->currentStep == 1) {
                $this->validate([
                    'firstName' => 'required|string|min:2|max:50',
                    'lastName' => 'required|string|min:2|max:50',
                    'email' => 'required|email|max:255',
                    'phone' => 'required|string|min:10|max:20',
                ]);
                \Log::info('Step 1 validation passed');
            } 
            // Step 2 validation
            elseif ($this->currentStep == 2) {
                $this->validate([
                    'address' => 'required|string|min:10|max:255',
                    'city' => 'required|string|min:2|max:100',
                    'state' => 'required|string|min:2|max:100',
                    'zipCode' => 'required|string|min:5|max:10',
                    'country' => 'required|string|max:100',
                ]);
                \Log::info('Step 2 validation passed');
            }

            // Move to next step
            if ($this->currentStep < 3) {
                $oldStep = $this->currentStep;
                $this->currentStep++;
                \Log::info('Step incremented from ' . $oldStep . ' to ' . $this->currentStep);
            }

            // When reaching step 3, create payment intent
            if ($this->currentStep == 3) {
                \Log::info('Reached step 3, creating payment intent...');
                $this->createPaymentIntent();
            }
            
            session()->flash('message', 'Moved to step ' . $this->currentStep);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed in nextStep', ['errors' => $e->errors()]);
            throw $e;
        } catch (\Exception $e) {
            \Log::error('nextStep error: ' . $e->getMessage());
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function previousStep()
    {
        try {
            if ($this->currentStep > 1) {
                $this->currentStep--;
                \Log::info('Moved back to step: ' . $this->currentStep);
            }
        } catch (\Exception $e) {
            \Log::error('Previous step error: ' . $e->getMessage());
        }
    }

    // Test method to verify Livewire is working
    public function testLivewire()
    {
        \Log::info('testLivewire method called');
        session()->flash('message', 'Livewire is working! Current step: ' . $this->currentStep);
        
        // Also test step increment
        if ($this->currentStep < 3) {
            $this->currentStep++;
            session()->flash('message', 'Livewire working! Moved to step: ' . $this->currentStep);
        }
    }

    public function createPaymentIntent()
    {
        try {
            \Log::info('Creating payment intent', [
                'amount' => $this->cartTotal,
                'stripe_secret_exists' => !empty(config('services.stripe.secret')),
                'cart_items_count' => count($this->cartItems)
            ]);

            if (empty(config('services.stripe.secret'))) {
                throw new \Exception('Stripe secret key not configured');
            }

            if ($this->cartTotal <= 0) {
                throw new \Exception('Invalid cart total: ' . $this->cartTotal);
            }

            Stripe::setApiKey(config('services.stripe.secret'));

            $paymentIntent = PaymentIntent::create([
                'amount' => round($this->cartTotal * 100), // Convert to cents
                'currency' => 'usd',
                'metadata' => [
                    'user_id' => Auth::id(),
                    'order_type' => $this->orderType,
                    'items_count' => count($this->cartItems)
                ],
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            $this->stripeClientSecret = $paymentIntent->client_secret;
            $this->paymentIntentId = $paymentIntent->id;
            
            \Log::info('Payment intent created successfully', [
                'payment_intent_id' => $this->paymentIntentId,
                'client_secret_length' => strlen($this->stripeClientSecret)
            ]);

            session()->flash('message', 'Payment intent created! Client secret: ' . substr($this->stripeClientSecret, 0, 20) . '...');

        } catch (\Exception $e) {
            \Log::error('Payment intent creation failed: ' . $e->getMessage());
            session()->flash('error', 'Payment setup failed: ' . $e->getMessage());
        }
    }

    // Test Stripe directly
    public function testStripeDirectly()
    {
        try {
            \Log::info('Testing Stripe directly...');
            
            Stripe::setApiKey(config('services.stripe.secret'));
            
            $intent = PaymentIntent::create([
                'amount' => 2000, // $20.00
                'currency' => 'Rs',
            ]);
            
            \Log::info('Direct Stripe test successful', ['intent_id' => $intent->id]);
            session()->flash('message', 'Direct Stripe test worked! Intent ID: ' . $intent->id);
            
        } catch (\Exception $e) {
            \Log::error('Direct Stripe test failed: ' . $e->getMessage());
            session()->flash('error', 'Direct Stripe test failed: ' . $e->getMessage());
        }
    }

    public function processOrder()
{
    if ($this->isProcessing) return;
    
    $this->isProcessing = true;

    try {
        \Log::info('Processing order', ['payment_intent_id' => $this->paymentIntentId]);

        // Verify payment with Stripe
        Stripe::setApiKey(config('services.stripe.secret'));
        $paymentIntent = PaymentIntent::retrieve($this->paymentIntentId);

        if ($paymentIntent->status !== 'succeeded') {
            throw new \Exception('Payment not completed. Status: ' . $paymentIntent->status);
        }

        \Log::info('Payment verified successfully');

        // Create order (NO transaction - direct creation)
        $order = Order::create([
            'user_id' => Auth::id(),
            'order_number' => Order::generateOrderNumber(),
            'type' => $this->orderType,
            'status' => 'paid',
            'items' => $this->prepareOrderItems(),
            'shipping_address' => [
                'first_name' => $this->firstName,
                'last_name' => $this->lastName,
                'address' => $this->address,
                'city' => $this->city,
                'state' => $this->state,
                'zip_code' => $this->zipCode,
                'country' => $this->country,
            ],
            'contact_info' => [
                'email' => $this->email,
                'phone' => $this->phone,
            ],
            'payment_info' => [
                'method' => 'stripe',
                'payment_intent_id' => $this->paymentIntentId,
            ],
            'subtotal' => $this->cartTotal,
            'total' => $this->cartTotal,
            'stripe_payment_intent_id' => $this->paymentIntentId,
        ]);

        \Log::info('Order created successfully', [
            'order_id' => $order->_id, 
            'order_number' => $order->order_number
        ]);

        // Update game statuses
        $this->updateGameStatuses($order);
        \Log::info('Game statuses updated');

        // Clear cart
        CartModel::where('user_id', Auth::id())->delete();
        \Log::info('Cart cleared');

        \Log::info('Order processing completed successfully');

        session()->flash('success', 'Order placed successfully! Order #' . $order->order_number);
        
        // Create the order success route if it doesn't exist
        try {
            return redirect()->route('buyer.order-success', ['orderId' => $order->_id]);
        } catch (\Exception $e) {
            // If route doesn't exist, redirect to dashboard with success message
            \Log::info('Order success route not found, redirecting to dashboard');
            return redirect()->route('buyer.dashboard');
        }

    } catch (\Exception $e) {
        \Log::error('Order processing failed', [
            'error' => $e->getMessage(),
            'payment_intent_id' => $this->paymentIntentId,
            'user_id' => Auth::id()
        ]);
        
        session()->flash('error', 'Failed to process order: ' . $e->getMessage());
        
        // Don't redirect on error, stay on checkout page
        return;
        
    } finally {
        $this->isProcessing = false;
    }
}

    private function prepareOrderItems()
    {
        $items = [];
        foreach ($this->cartItems as $item) {
            $game = Game::find($item['game_id']);
            if ($game) {
                $items[] = [
                    'game_id' => $item['game_id'],
                    'game_name' => $game->name,
                    'game_image' => $game->image,
                    'seller_id' => $game->seller_id,
                    'type' => $game->is_for_rent ? 'rental' : 'purchase',
                    'quantity' => $item['quantity'],
                    'rental_days' => $item['rental_days'] ?? 0,
                    'unit_price' => $game->is_for_rent ? $game->rent_price : $game->sell_price,
                    'total_price' => $game->is_for_rent ? 
                        ($game->rent_price * $item['rental_days'] * $item['quantity']) : 
                        ($game->sell_price * $item['quantity']),
                ];
            }
        }
        return $items;
    }

    private function updateGameStatuses($order)
    {
        foreach ($this->cartItems as $item) {
            $game = Game::find($item['game_id']);
            if ($game) {
                if ($game->is_for_rent) {
                    // Mark as rented
                    GameStatus::updateOrCreate(
                        ['game_id' => $item['game_id']],
                        [
                            'status' => 'rented',
                            'rented_to_user_id' => Auth::id(),
                            'rental_start_date' => now(),
                            'rental_end_date' => now()->addDays($item['rental_days']),
                            'order_id' => $order->_id,
                        ]
                    );
                } else {
                    // Mark as sold
                    GameStatus::updateOrCreate(
                        ['game_id' => $item['game_id']],
                        [
                            'status' => 'sold',
                            'order_id' => $order->_id,
                        ]
                    );
                }
            }
        }
    }

    public function render()
    {
        return view('livewire.buyer.checkout');
    }
}