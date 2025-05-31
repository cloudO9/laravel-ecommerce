<div>
    <!-- Page Header -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-500 rounded-2xl mb-4 shadow-lg shadow-blue-500/25">
            <span class="text-2xl">🛒</span>
        </div>
        <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent mb-2">
            Shopping Cart
        </h1>
        <p class="text-slate-400">Review your selected games before checkout</p>
    </div>

    <!-- Success/Error Messages -->
    @if (session()->has('message'))
        <div class="bg-emerald-500/20 border border-emerald-500/40 text-emerald-300 px-6 py-4 rounded-xl mb-8 flex items-center">
            <span class="text-2xl mr-3">✅</span>
            <span class="font-medium">{{ session('message') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-500/20 border border-red-500/40 text-red-300 px-6 py-4 rounded-xl mb-8 flex items-center">
            <span class="text-2xl mr-3">⚠️</span>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    @if($cartItems->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2">
                <div class="bg-slate-800/60 backdrop-blur-lg border border-slate-700/50 rounded-2xl p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-white flex items-center gap-2">
                            <span>📦</span>
                            Cart Items ({{ $cartCount }})
                        </h2>
                        <button wire:click="clearCart" 
                            class="text-red-400 hover:text-red-300 px-4 py-2 rounded-lg hover:bg-red-500/10 transition-colors text-sm font-medium">
                            Clear All
                        </button>
                    </div>

                    <div class="space-y-4">
                        @foreach($cartItems as $item)
                            <div class="bg-slate-900/60 rounded-xl p-6 border border-slate-700/50 hover:border-slate-600/50 transition-colors">
                                <div class="flex items-start gap-4">
                                    <!-- Game Image -->
                                    <div class="w-20 h-20 bg-slate-700/50 rounded-xl flex items-center justify-center flex-shrink-0">
                                        @if($item->game && $item->game->image)
                                            <img src="{{ Storage::url($item->game->image) }}" alt="{{ $item->game->name }}" 
                                                 class="w-full h-full object-cover rounded-xl">
                                        @else
                                            <span class="text-3xl opacity-50">🎮</span>
                                        @endif
                                    </div>

                                    <!-- Game Info -->
                                    <div class="flex-1">
                                        <div class="flex items-start justify-between mb-2">
                                            <div>
                                                <h3 class="font-bold text-white text-lg mb-1">
                                                    {{ $item->game->name ?? 'Unknown Game' }}
                                                </h3>
                                                <div class="flex items-center gap-4 text-sm text-slate-400">
                                                    <span class="flex items-center gap-1">
                                                        <span>⭐</span>
                                                        {{ $item->game->condition ?? 'Unknown' }}
                                                    </span>
                                                    @if($item->game && $item->game->is_for_rent)
                                                        <span class="bg-purple-600/20 text-purple-300 px-2 py-1 rounded-full text-xs">
                                                            📅 For Rent
                                                        </span>
                                                    @else
                                                        <span class="bg-emerald-600/20 text-emerald-300 px-2 py-1 rounded-full text-xs">
                                                            💰 For Sale
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <button wire:click="removeFromCart('{{ $item->_id }}')" 
                                                class="text-red-400 hover:text-red-300 p-2 rounded-lg hover:bg-red-500/10 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>

                                        <!-- Price Info -->
                                        <div class="mb-4">
                                            @if($item->game && $item->game->is_for_rent)
                                                <p class="text-purple-400 font-semibold">
                                                    ${{ number_format($item->game->rent_price, 2) }}/day
                                                </p>
                                            @else
                                                <p class="text-emerald-400 font-semibold">
                                                    ${{ number_format($item->game->sell_price ?? 0, 2) }}
                                                </p>
                                            @endif
                                        </div>

                                        <!-- Quantity and Rental Controls -->
                                        <div class="mb-4">
                                            @if($item->game && $item->game->is_for_rent)
                                                <!-- FOR RENTAL ITEMS -->
                                                <div class="grid grid-cols-2 gap-4">
                                                    <!-- Quantity -->
                                                    <div>
                                                        <label class="block text-xs text-slate-400 mb-2">Quantity</label>
                                                        <div class="flex items-center bg-slate-800/60 rounded-lg">
                                                            <button wire:click="updateQuantity('{{ $item->_id }}', {{ $item->quantity - 1 }})" 
                                                                class="p-3 text-slate-400 hover:text-white transition-colors">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                                </svg>
                                                            </button>
                                                            <span class="flex-1 text-center text-white font-semibold">{{ $item->quantity }}</span>
                                                            <button wire:click="updateQuantity('{{ $item->_id }}', {{ $item->quantity + 1 }})" 
                                                                class="p-3 text-slate-400 hover:text-white transition-colors">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <!-- Rental Days -->
                                                    <div>
                                                        <label class="block text-xs text-slate-400 mb-2 text-center">Rental Days</label>
                                                        <div class="flex items-center justify-center gap-2 mb-2">
                                                            <button wire:click="updateRentalDays('{{ $item->_id }}', {{ max(1, $item->rental_days - 1) }})" 
                                                                class="bg-slate-700/60 hover:bg-purple-600/60 text-white p-2 rounded-lg transition-colors
                                                                       {{ $item->rental_days <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                                {{ $item->rental_days <= 1 ? 'disabled' : '' }}>
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                                </svg>
                                                            </button>
                                                            
                                                            <div class="bg-slate-800/60 px-3 py-1 rounded-lg min-w-[60px] text-center">
                                                                <span class="text-lg font-bold text-white">{{ $item->rental_days }}</span>
                                                                <span class="text-slate-400 text-xs block leading-none">{{ $item->rental_days == 1 ? 'day' : 'days' }}</span>
                                                            </div>
                                                            
                                                            <button wire:click="updateRentalDays('{{ $item->_id }}', {{ min(30, $item->rental_days + 1) }})" 
                                                                class="bg-slate-700/60 hover:bg-purple-600/60 text-white p-2 rounded-lg transition-colors
                                                                       {{ $item->rental_days >= 30 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                                {{ $item->rental_days >= 30 ? 'disabled' : '' }}>
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                        
                                                        <!-- Show rental calculation -->
                                                        <div class="text-center">
                                                            <span class="text-purple-400 font-bold text-xs">
                                                                ${{ number_format($item->game->rent_price * $item->rental_days, 2) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <!-- FOR SALE ITEMS -->
                                                <div class="grid grid-cols-1 gap-4">
                                                    <!-- Quantity Only -->
                                                    <div>
                                                        <label class="block text-xs text-slate-400 mb-2">Quantity</label>
                                                        <div class="flex items-center bg-slate-800/60 rounded-lg">
                                                            <button wire:click="updateQuantity('{{ $item->_id }}', {{ $item->quantity - 1 }})" 
                                                                class="p-3 text-slate-400 hover:text-white transition-colors">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                                </svg>
                                                            </button>
                                                            <span class="flex-1 text-center text-white font-semibold">{{ $item->quantity }}</span>
                                                            <button wire:click="updateQuantity('{{ $item->_id }}', {{ $item->quantity + 1 }})" 
                                                                class="p-3 text-slate-400 hover:text-white transition-colors">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Individual Item Actions - WITH AVAILABILITY CHECKS -->
                                        <div class="bg-slate-800/40 rounded-lg p-4 mb-4">
                                            <div class="flex items-center justify-between mb-3">
                                                <span class="text-slate-300 font-medium text-sm">Quick Actions:</span>
                                                <span class="text-emerald-400 font-bold">{{ $item->getFormattedTotalPrice() }}</span>
                                            </div>

                                            @php
                                                $availabilityStatus = $this->getItemAvailabilityStatus($item);
                                                $canPurchase = $this->canPurchaseItem($item);
                                            @endphp

                                            @if($availabilityStatus === 'sold')
                                                <!-- Game Sold -->
                                                <div class="bg-red-500/10 border border-red-500/20 rounded-lg p-3 text-center">
                                                    <div class="text-red-400 font-semibold text-sm mb-1">❌ Game Sold</div>
                                                    <div class="text-red-300 text-xs">This game has been purchased by someone else</div>
                                                    <button wire:click="removeFromCart('{{ $item->_id }}')" 
                                                        class="mt-2 text-red-400 hover:text-red-300 text-xs underline">
                                                        Remove from cart
                                                    </button>
                                                </div>
                                            @elseif($availabilityStatus === 'rented' && !$item->game->is_for_rent)
                                                <!-- Game Currently Rented (for sale games) -->
                                                <div class="bg-yellow-500/10 border border-yellow-500/20 rounded-lg p-3 text-center">
                                                    <div class="text-yellow-400 font-semibold text-sm mb-1">📅 Currently Rented</div>
                                                    <div class="text-yellow-300 text-xs">Wait until it becomes available</div>
                                                    <div class="text-yellow-200 text-xs mt-1 opacity-75">We'll notify you when it's back</div>
                                                </div>
                                            @elseif($availabilityStatus === 'rented' && $item->game->is_for_rent)
                                                <!-- Rental Game Currently Rented -->
                                                <div class="bg-yellow-500/10 border border-yellow-500/20 rounded-lg p-3 text-center">
                                                    <div class="text-yellow-400 font-semibold text-sm mb-1">📅 Currently Rented</div>
                                                    <div class="text-yellow-300 text-xs">This game is rented by someone else</div>
                                                    <div class="text-yellow-200 text-xs mt-1 opacity-75">Please wait until it's returned</div>
                                                </div>
                                            @else
                                                <!-- Game Available - Show Action Buttons -->
                                                <div class="flex gap-2">
                                                    @if($item->game && $item->game->is_for_rent)
                                                        <!-- Rent This Item Button -->
                                                        <button wire:click="rentIndividualItem('{{ $item->_id }}')" 
                                                            class="flex-1 bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 
                                                                   text-white font-semibold py-2.5 px-4 rounded-lg text-sm
                                                                   transform hover:-translate-y-0.5 hover:shadow-lg hover:shadow-purple-500/25
                                                                   transition-all duration-200 flex items-center justify-center gap-2">
                                                            <span>📅</span>
                                                            <span>Rent Now</span>
                                                            <span class="text-xs opacity-80">({{ $item->rental_days }}d)</span>
                                                        </button>
                                                    @else
                                                        <!-- Buy This Item Button -->
                                                        <button wire:click="buyIndividualItem('{{ $item->_id }}')" 
                                                            class="flex-1 bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 
                                                                   text-white font-semibold py-2.5 px-4 rounded-lg text-sm
                                                                   transform hover:-translate-y-0.5 hover:shadow-lg hover:shadow-emerald-500/25
                                                                   transition-all duration-200 flex items-center justify-center gap-2">
                                                            <span>💰</span>
                                                            <span>Buy Now</span>
                                                            <span class="text-xs opacity-80">({{ $item->quantity }}x)</span>
                                                        </button>
                                                    @endif
                                                    
                                                    <!-- Skip cart hint -->
                                                    <div class="flex items-center text-slate-400 text-xs px-2">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        <span>Skip cart</span>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Item Total -->
                                        <div class="pt-4 border-t border-slate-700/50 flex justify-between items-center">
                                            <span class="text-slate-400">Item Total:</span>
                                            <span class="text-emerald-400 font-bold text-lg">{{ $item->getFormattedTotalPrice() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="lg:col-span-1">
                <div class="bg-slate-800/60 backdrop-blur-lg border border-slate-700/50 rounded-2xl p-6 sticky top-6">
                    <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                        <span>💰</span>
                        Order Summary
                    </h2>

                    <!-- Cart Stats -->
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="text-slate-400">Items in cart:</span>
                            <span class="text-white font-semibold">{{ $cartCount }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-400">Subtotal:</span>
                            <span class="text-white font-semibold">${{ number_format($cartTotal, 2) }}</span>
                        </div>
                        <div class="border-t border-slate-700/50 pt-4">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-semibold text-white">Total:</span>
                                <span class="text-2xl font-bold text-emerald-400">${{ number_format($cartTotal, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Bulk vs Individual Notice -->
                    <div class="bg-blue-500/10 border border-blue-500/20 rounded-lg p-4 mb-6">
                        <div class="flex items-start gap-3">
                            <span class="text-blue-400 text-lg">💡</span>
                            <div>
                                <h4 class="text-blue-300 font-semibold text-sm mb-1">Pro Tip</h4>
                                <p class="text-blue-200 text-xs leading-relaxed">
                                    Use "Buy Now" or "Rent Now" on individual items to skip the cart and checkout instantly, 
                                    or checkout all items together below.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-3">
                        <button wire:click="checkout" 
                            class="w-full bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-700 hover:to-emerald-600
                                   text-white font-bold py-4 px-6 rounded-xl
                                   transform hover:-translate-y-1 hover:shadow-lg hover:shadow-emerald-500/25
                                   transition-all duration-300 flex items-center justify-center gap-2">
                            <span>💳</span>
                            Checkout All Items
                        </button>
                        <button wire:click="continueShopping" 
                            class="w-full bg-slate-700/60 hover:bg-slate-600/60 text-slate-200 
                                   py-3 px-6 rounded-xl font-medium transition-colors flex items-center justify-center gap-2">
                            <span>🛍️</span>
                            Continue Shopping
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Empty Cart -->
        <div class="text-center py-20">
            <div class="w-32 h-32 bg-slate-700/30 rounded-3xl flex items-center justify-center mx-auto mb-6">
                <span class="text-6xl opacity-50">🛒</span>
            </div>
            <h3 class="text-2xl font-bold text-slate-300 mb-4">Your cart is empty</h3>
            <p class="text-slate-400 mb-8 max-w-md mx-auto">
                Browse our amazing collection of games and add them to your cart to get started!
            </p>
            <button wire:click="continueShopping" 
                class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 
                       text-white px-8 py-4 rounded-xl font-bold text-lg
                       transform hover:-translate-y-1 hover:shadow-lg
                       transition-all duration-300 flex items-center gap-3 mx-auto">
                <span>🎮</span>
                Browse Games
                <span>🚀</span>
            </button>
        </div>
    @endif
</div>