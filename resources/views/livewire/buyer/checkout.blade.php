<div>
    <!-- Enhanced Debug Panel -->
    <div class="bg-yellow-600/10 border border-yellow-600/30 rounded-lg p-4 mb-6">
        <h3 class="text-yellow-300 font-bold mb-2">Debug Info:</h3>
        <div class="text-yellow-200 text-sm space-y-1">
            <div>Current Step: <strong>{{ $currentStep }}</strong></div>
            <div>Cart Items: <strong>{{ count($cartItems) }}</strong></div>
            <div>Cart Total: <strong>${{ number_format($cartTotal, 2) }}</strong></div>
            <div>Order Type: <strong>{{ $orderType }}</strong></div>
            <div>Stripe Key: <strong>{{ config('services.stripe.key') ? 'Set ' : 'Missing ' }}</strong></div>
            <div>Stripe Secret: <strong>{{ config('services.stripe.secret') ? 'Set ' : 'Missing ' }}</strong></div>
            <div>Client Secret: <strong>{{ $stripeClientSecret ? 'Generated ' : 'Missing ' }}</strong></div>
            <div>Payment Intent ID: <strong>{{ $paymentIntentId ?: 'Not created' }}</strong></div>
        </div>
        <div class="flex gap-2 mt-2">
            <button wire:click="testLivewire" class="bg-yellow-600 text-white px-3 py-1 rounded text-sm">
                Test Livewire Connection
            </button>
            @if($currentStep == 3)
                <button wire:click="testStripeDirectly" class="bg-purple-600 text-white px-3 py-1 rounded text-sm">
                    🧪 Test Stripe Direct
                </button>
            @endif
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if (session()->has('message'))
        <div class="bg-emerald-500/20 border border-emerald-500/40 text-emerald-300 px-6 py-4 rounded-xl mb-8 flex items-center">
            <span class="text-2xl mr-3"></span>
            <span class="font-medium">{{ session('message') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-500/20 border border-red-500/40 text-red-300 px-6 py-4 rounded-xl mb-8 flex items-center">
            <span class="text-2xl mr-3">⚠️</span>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Progress Bar -->
    <div class="mb-8">
        <div class="flex items-center">
            @for($i = 1; $i <= 3; $i++)
                <div class="flex items-center {{ $i < 3 ? 'flex-1' : '' }}">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center 
                        {{ $currentStep >= $i ? 'bg-emerald-500 text-white' : 'bg-slate-700 text-slate-400' }}">
                        {{ $i }}
                    </div>
                    @if($i < 3)
                        <div class="flex-1 h-1 mx-4 
                            {{ $currentStep > $i ? 'bg-emerald-500' : 'bg-slate-700' }}"></div>
                    @endif
                </div>
            @endfor
        </div>
        <div class="flex justify-between mt-2 text-sm">
            <span class="{{ $currentStep >= 1 ? 'text-emerald-400' : 'text-slate-400' }}">Contact Info</span>
            <span class="{{ $currentStep >= 2 ? 'text-emerald-400' : 'text-slate-400' }}">Shipping</span>
            <span class="{{ $currentStep >= 3 ? 'text-emerald-400' : 'text-slate-400' }}">Payment</span>
        </div>
    </div>

    <!-- Step 1: Contact Information -->
    @if($currentStep == 1)
        <div class="bg-slate-800/60 backdrop-blur-lg border border-slate-700/50 rounded-2xl p-6">
            <h2 class="text-xl font-bold text-white mb-6">Contact Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">First Name *</label>
                    <input type="text" wire:model="firstName" class="input-gaming w-full" placeholder="Enter first name">
                    @error('firstName') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Last Name *</label>
                    <input type="text" wire:model="lastName" class="input-gaming w-full" placeholder="Enter last name">
                    @error('lastName') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Email *</label>
                    <input type="email" wire:model="email" class="input-gaming w-full" placeholder="Enter email">
                    @error('email') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Phone *</label>
                    <input type="tel" wire:model="phone" class="input-gaming w-full" placeholder="Enter phone number">
                    @error('phone') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
    @endif

    <!-- Step 2: Shipping Address -->
    @if($currentStep == 2)
        <div class="bg-slate-800/60 backdrop-blur-lg border border-slate-700/50 rounded-2xl p-6">
            <h2 class="text-xl font-bold text-white mb-6">Shipping Address</h2>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Street Address *</label>
                    <input type="text" wire:model="address" class="input-gaming w-full" placeholder="123 Main Street">
                    @error('address') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">City *</label>
                        <input type="text" wire:model="city" class="input-gaming w-full" placeholder="New York">
                        @error('city') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">State *</label>
                        <input type="text" wire:model="state" class="input-gaming w-full" placeholder="NY">
                        @error('state') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">ZIP Code *</label>
                        <input type="text" wire:model="zipCode" class="input-gaming w-full" placeholder="10001">
                        @error('zipCode') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Country *</label>
                    <select wire:model="country" class="input-gaming w-full">
                        <option value="United States">United States</option>
                        <option value="Canada">Canada</option>
                        <option value="United Kingdom">United Kingdom</option>
                    </select>
                </div>
            </div>
        </div>
    @endif

    <!-- Step 3: Payment -->
    @if($currentStep == 3)
        <div class="bg-slate-800/60 backdrop-blur-lg border border-slate-700/50 rounded-2xl p-6">
            <h2 class="text-xl font-bold text-white mb-6">Payment Information</h2>
            
            <!-- Hidden elements for JavaScript to read current values -->
            <input type="hidden" id="stripe-client-secret" value="{{ $stripeClientSecret }}">
            <input type="hidden" id="stripe-publishable-key" value="{{ config('services.stripe.key') }}">
            <input type="hidden" id="current-step" value="{{ $currentStep }}">
            
            <!-- Payment Status Check -->
            <div class="bg-blue-600/10 border border-blue-600/30 rounded-lg p-3 mb-4 text-sm">
                <div class="text-blue-300">
                    <div>Client Secret: {{ $stripeClientSecret ? ' Generated (' . strlen($stripeClientSecret) . ' chars)' : '❌ Missing' }}</div>
                    <div>Payment Intent: {{ $paymentIntentId ?: ' Not created' }}</div>
                    <div>Cart Total: ${{ number_format($cartTotal, 2) }}</div>
                    <div>Stripe Keys: {{ config('services.stripe.secret') ? ' Configured' : ' Missing' }}</div>
                </div>
                
                @if(!$stripeClientSecret)
                    <div class="mt-3 space-x-2">
                        <button wire:click="createPaymentIntent" class="bg-red-600 text-white px-3 py-1 rounded text-sm">
                            🔄 Create Payment Intent
                        </button>
                        <button onclick="location.reload()" class="bg-gray-600 text-white px-3 py-1 rounded text-sm">
                            🔄 Refresh Page
                        </button>
                    </div>
                @endif
            </div>
            
            @if($stripeClientSecret)
                <!-- Stripe Payment Form Container -->
                <div id="payment-element" class="mb-4 p-6 border-2 border-dashed border-slate-600 rounded-lg min-h-[250px] bg-slate-700/30">
                    <div class="text-center text-slate-400 flex items-center justify-center h-full">
                        <div>
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-emerald-500 mx-auto mb-2"></div>
                            <div>Loading Stripe payment form...</div>
                            <div class="text-xs mt-2">Credit card fields will appear here</div>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Errors -->
                <div id="payment-errors" class="text-red-400 text-sm mb-4 p-3 rounded-lg bg-red-500/10 border border-red-500/30" style="display: none;"></div>
                
                <!-- Debug Buttons -->
                <div class="text-center mb-4 space-x-2">
                    <button onclick="debugStripeStatus()" class="bg-purple-600 text-white px-3 py-1 rounded text-sm">
                        🔧 Debug Stripe
                    </button>
                    <button onclick="forceInitializeStripe()" class="bg-green-600 text-white px-3 py-1 rounded text-sm">
                        🔄 Force Load Form
                    </button>
                </div>
            @else
                <div class="text-center py-12 text-red-400">
                    <div class="text-4xl mb-4">⚠️</div>
                    <div class="text-xl mb-2">Payment Not Ready</div>
                    <div class="text-sm">Click "Create Payment Intent" above to initialize payment</div>
                </div>
            @endif
        </div>
    @endif

    <!-- Order Summary -->
    <div class="bg-slate-800/60 backdrop-blur-lg border border-slate-700/50 rounded-2xl p-6 mt-6">
        <h3 class="text-lg font-bold text-white mb-4">Order Summary</h3>
        
        <div class="space-y-3">
            @foreach($cartItems as $item)
                @php
                    $game = \App\Models\Game::find($item['game_id']);
                @endphp
                @if($game)
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="text-white">{{ $game->name }}</span>
                            @if($game->is_for_rent)
                                <span class="text-slate-400 text-sm">({{ $item['rental_days'] }} days)</span>
                            @endif
                        </div>
                        <span class="text-emerald-400">
                            ${{ number_format($game->is_for_rent ? 
                                ($game->rent_price * $item['rental_days'] * $item['quantity']) : 
                                ($game->sell_price * $item['quantity']), 2) }}
                        </span>
                    </div>
                @endif
            @endforeach
            
            <div class="border-t border-slate-700 pt-3">
                <div class="flex justify-between items-center text-lg font-bold">
                    <span class="text-white">Total:</span>
                    <span class="text-emerald-400">${{ number_format($cartTotal, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-between mt-6">
        @if($currentStep > 1)
            <button wire:click="previousStep" class="btn-gaming-secondary px-6 py-3">
                ← Previous
            </button>
        @else
            <div></div>
        @endif

        @if($currentStep < 3)
            <button wire:click="nextStep" class="btn-gaming-primary px-6 py-3">
                <span wire:loading.remove wire:target="nextStep">Next →</span>
                <span wire:loading wire:target="nextStep">Loading...</span>
            </button>
        @else
            <button id="submit-payment" class="btn-gaming-primary px-6 py-3" 
                    style="display: {{ $stripeClientSecret ? 'block' : 'none' }}">
                <span id="payment-button-text">
                    Complete Order (${{ number_format($cartTotal, 2) }})
                </span>
            </button>
        @endif
    </div>
</div>

<!-- Load Stripe JavaScript -->
<script src="https://js.stripe.com/v3/"></script>

<script>
// Global Stripe variables
let stripe = null;
let elements = null;
let paymentElement = null;

console.log('=== STRIPE SCRIPT LOADED ===');

// Get current values from hidden HTML elements (updated dynamically by Livewire)
function getCurrentStripeConfig() {
    const clientSecretEl = document.getElementById('stripe-client-secret');
    const publishableKeyEl = document.getElementById('stripe-publishable-key');
    const currentStepEl = document.getElementById('current-step');
    
    const clientSecret = clientSecretEl ? clientSecretEl.value : '';
    const publishableKey = publishableKeyEl ? publishableKeyEl.value : '';
    const currentStep = currentStepEl ? parseInt(currentStepEl.value) : 0;
    
    console.log('Getting current config from DOM:', {
        clientSecret: clientSecret ? 'EXISTS (' + clientSecret.length + ' chars)' : 'MISSING',
        publishableKey: publishableKey ? 'EXISTS' : 'MISSING',
        currentStep: currentStep
    });
    
    return {
        clientSecret: clientSecret,
        publishableKey: publishableKey,
        currentStep: currentStep
    };
}

// Debug function
function debugStripeStatus() {
    const config = getCurrentStripeConfig();
    
    console.log('=== STRIPE DEBUG STATUS ===');
    console.log('Stripe JS loaded:', typeof Stripe !== 'undefined');
    console.log('Stripe instance created:', !!stripe);
    console.log('Elements created:', !!elements);
    console.log('Payment element created:', !!paymentElement);
    console.log('Container exists:', !!document.getElementById('payment-element'));
    console.log('Client secret from DOM:', config.clientSecret ? 'EXISTS (' + config.clientSecret.length + ' chars)' : 'MISSING');
    console.log('Publishable key from DOM:', config.publishableKey ? 'EXISTS' : 'MISSING');
    console.log('Current step:', config.currentStep);
    
    // Show actual values for debugging
    console.log('Raw client secret:', config.clientSecret.substring(0, 20) + '...');
    console.log('Raw publishable key:', config.publishableKey.substring(0, 20) + '...');
    
    const container = document.getElementById('payment-element');
    if (container) {
        console.log('Container HTML length:', container.innerHTML.length);
        console.log('Container content preview:', container.innerHTML.substring(0, 100));
    }
    
    alert(' Check browser console for detailed debug info');
}

// Force initialize Stripe
function forceInitializeStripe() {
    console.log('=== FORCING STRIPE INITIALIZATION ===');
    
    // Reset everything
    stripe = null;
    elements = null;
    paymentElement = null;
    
    // Try to re-initialize
    initializeStripe();
}

// Initialize Stripe payment form
function initializeStripe() {
    console.log('=== INITIALIZING STRIPE ===');
    
    // Get fresh config from DOM
    const config = getCurrentStripeConfig();
    
    console.log('Initializing with config:', {
        hasClientSecret: !!config.clientSecret,
        hasPublishableKey: !!config.publishableKey,
        currentStep: config.currentStep
    });
    
    // Check prerequisites
    if (!config.clientSecret) {
        console.error('No client secret available');
        
        const container = document.getElementById('payment-element');
        if (container) {
            container.innerHTML = `
                <div class="text-center text-red-400 py-8">
                    <div class="text-2xl mb-2"></div>
                    <div class="font-bold mb-2">Client Secret Missing</div>
                    <div class="text-sm mb-4">Payment intent not created yet</div>
                    <button onclick="window.location.reload()" 
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg mr-2">
                        Refresh Page
                    </button>
                </div>
            `;
        }
        return;
    }
    
    if (!config.publishableKey) {
        console.error(' No publishable key available');
        return;
    }
    
    if (typeof Stripe === 'undefined') {
        console.error(' Stripe JS not loaded');
        return;
    }
    
    const container = document.getElementById('payment-element');
    if (!container) {
        console.error(' Payment element container not found');
        return;
    }
    
    if (stripe) {
        console.log('Stripe already initialized');
        return;
    }
    
    try {
        // Create Stripe instance
        console.log('Creating Stripe instance...');
        stripe = Stripe(config.publishableKey);
        
        // Create elements
        console.log('Creating Stripe elements...');
        elements = stripe.elements({
            clientSecret: config.clientSecret,
            appearance: {
                theme: 'night',
                variables: {
                    colorPrimary: '#00ff88',
                    colorBackground: '#334155',
                    colorText: '#ffffff',
                    colorDanger: '#ef4444',
                    fontFamily: 'ui-sans-serif, system-ui, sans-serif',
                    spacingUnit: '8px',
                    borderRadius: '8px',
                }
            }
        });
        
        // Create payment element
        console.log('Creating payment element...');
        paymentElement = elements.create('payment');
        
        // Clear container and mount
        console.log('Mounting payment element...');
        container.innerHTML = '<div class="text-center py-4 text-slate-400">Mounting payment form...</div>';
        
        paymentElement.mount('#payment-element');
        
        // Set up event listeners
        paymentElement.on('ready', function() {
            console.log('Payment element is ready and mounted');
            container.style.border = '2px solid #00ff88';
            container.style.backgroundColor = '#1e293b';
            
            // Show success message
            const successDiv = document.createElement('div');
            successDiv.className = 'text-center text-green-400 text-sm mt-2';
            successDiv.textContent = ' Payment form loaded successfully';
            container.appendChild(successDiv);
        });
        
        paymentElement.on('change', function(event) {
            console.log('Payment element changed:', event.complete ? 'Complete' : 'Incomplete');
        });
        
        paymentElement.on('focus', function() {
            console.log('Payment element focused');
        });
        
        console.log(' Stripe initialization completed successfully');
        
    } catch (error) {
        console.error(' Stripe initialization failed:', error);
        
        if (container) {
            container.innerHTML = `
                <div class="text-center text-red-400 py-8">
                    <div class="text-2xl mb-2"></div>
                    <div class="font-bold mb-2">Payment Form Failed to Load</div>
                    <div class="text-sm mb-4">${error.message}</div>
                    <button onclick="forceInitializeStripe()" 
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                        Try Again
                    </button>
                </div>
            `;
        }
    }
}

// Try to initialize Stripe when conditions are met
function attemptStripeInit() {
    const config = getCurrentStripeConfig();
    
    console.log('Checking init conditions:', {
        currentStep: config.currentStep,
        hasClientSecret: !!config.clientSecret,
        hasPublishableKey: !!config.publishableKey
    });
    
    if (config.currentStep === 3 && config.clientSecret && config.publishableKey) {
        console.log('All conditions met, attempting Stripe initialization...');
        setTimeout(initializeStripe, 500);
    } else {
        console.log('Conditions not met for Stripe init');
        if (config.currentStep === 3 && !config.clientSecret) {
            console.log('On step 3 but no client secret - payment intent may need to be created');
        }
    }
}

// Initialize on various events
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded');
    attemptStripeInit();
});

// Livewire hooks
document.addEventListener('livewire:load', function() {
    console.log('Livewire loaded');
    attemptStripeInit();
});

// Re-check after Livewire updates (most important for dynamic updates)
if (typeof Livewire !== 'undefined') {
    Livewire.hook('message.processed', function() {
        console.log('Livewire message processed - checking for Stripe init');
        setTimeout(attemptStripeInit, 500);
    });
}

// Handle payment submission
document.addEventListener('click', function(e) {
    if (e.target.id === 'submit-payment' || e.target.closest('#submit-payment')) {
        e.preventDefault();
        
        console.log('=== PAYMENT SUBMISSION STARTED ===');
        
        if (!stripe || !elements) {
            console.error('Stripe not initialized');
            alert('Payment system not ready. Please click "Force Load Form" button and try again.');
            return;
        }
        
        const button = document.getElementById('submit-payment');
        const buttonText = document.getElementById('payment-button-text');
        
        // Update button state
        button.disabled = true;
        buttonText.innerHTML = '<div class="flex items-center gap-2"><div class="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full"></div> Processing...</div>';
        
        console.log('Confirming payment with Stripe...');
        
        stripe.confirmPayment({
            elements,
            redirect: 'if_required'
        }).then(function(result) {
            // Reset button state
            button.disabled = false;
            buttonText.innerHTML = 'Complete Order (${{ number_format($cartTotal, 2) }})';
            
            if (result.error) {
                console.error(' Payment failed:', result.error);
                const errorDiv = document.getElementById('payment-errors');
                errorDiv.textContent = result.error.message;
                errorDiv.style.display = 'block';
            } else if (result.paymentIntent.status === 'succeeded') {
                console.log('Payment succeeded, processing order...');
                buttonText.innerHTML = 'Payment Successful - Processing Order...';
                
                // Call Livewire method to process the order
                @this.call('processOrder');
            } else {
                console.log('Payment status:', result.paymentIntent.status);
            }
        }).catch(function(error) {
            console.error(' Payment confirmation error:', error);
            button.disabled = false;
            buttonText.innerHTML = 'Complete Order (${{ number_format($cartTotal, 2) }})';
        });
    }
});

// Auto-initialize if conditions are already met
setTimeout(function() {
    console.log('Auto-checking Stripe initialization after 1 second...');
    attemptStripeInit();
}, 1000);
</script>