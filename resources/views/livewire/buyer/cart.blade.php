<div class="min-h-screen py-8 px-4">
    <!-- Page Header -->
    <div class="text-center mb-16">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 rounded-3xl mb-6 shadow-2xl shadow-purple-500/30">
            <span class="text-4xl transform transition-transform duration-300 hover:scale-110">🛒</span>
        </div>
        <h1 class="text-4xl font-extrabold bg-gradient-to-r from-blue-400 via-purple-400 to-pink-400 bg-clip-text text-transparent mb-4 tracking-tight">
            Your Shopping Cart
        </h1>
        <p class="text-slate-400 text-lg">Review your selected games and proceed to an epic checkout!</p>
    </div>

    <!-- Success/Error Messages -->
    @if (session()->has('message'))
        <div class="bg-emerald-500/25 border border-emerald-500/50 text-emerald-200 px-6 py-5 rounded-xl mb-10 flex items-center shadow-lg shadow-emerald-500/20 max-w-4xl mx-auto">
            <span class="text-3xl mr-4 animate-pulse">✅</span>
            <span class="font-semibold text-md">{{ session('message') }}</span>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="bg-red-500/25 border border-red-500/50 text-red-200 px-6 py-5 rounded-xl mb-10 flex items-center shadow-lg shadow-red-500/20 max-w-4xl mx-auto">
            <span class="text-3xl mr-4 animate-bounce">⚠️</span>
            <span class="font-semibold text-md">{{ session('error') }}</span>
        </div>
    @endif

    @if($cartItems->count() > 0)
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 xl:gap-12">
                <!-- Cart Items Section -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Cart Header -->
                    <div class="bg-slate-800/70 backdrop-blur-xl border border-slate-700/60 rounded-2xl p-6 shadow-2xl shadow-slate-900/50">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between">
                            <h2 class="text-2xl font-bold text-white flex items-center gap-3 mb-4 sm:mb-0">
                                <span class="text-3xl">📦</span>
                                Your Cart Items ({{ $cartCount }})
                            </h2>
                            <button wire:click="clearCart" 
                                class="text-red-400 hover:text-white bg-red-500/10 hover:bg-red-500/80 px-6 py-3 rounded-lg transition-all duration-300 text-sm font-semibold flex items-center gap-2 group">
                                <svg class="w-5 h-5 group-hover:animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                Clear All
                            </button>
                        </div>
                    </div>

                    <!-- Cart Items List -->
                    @foreach($cartItems as $item)
                        <div class="bg-slate-800/70 backdrop-blur-xl border border-slate-700/60 rounded-2xl overflow-hidden shadow-2xl shadow-slate-900/50 hover:border-purple-500/50 transition-all duration-300">
                            
                            <!-- Game Header Section -->
                            <div class="bg-slate-900/40 p-6 border-b border-slate-700/50">
                                <div class="flex items-start gap-6">
                                    <!-- Game Image -->
                                    <div class="w-24 h-32 bg-slate-700/60 rounded-xl flex items-center justify-center flex-shrink-0 overflow-hidden shadow-md">
                                        @if($item->game && $item->game->image)
                                            <img src="{{ Storage::url($item->game->image) }}" alt="{{ $item->game->name }}" 
                                                 class="w-full h-full object-cover transition-transform duration-300 hover:scale-110">
                                        @else
                                            <span class="text-4xl opacity-60">🎮</span>
                                        @endif
                                    </div>
                                    
                                    <!-- Game Basic Info -->
                                    <div class="flex-1">
                                        <div class="flex items-start justify-between mb-4">
                                            <div>
                                                <h3 class="font-bold text-white text-xl mb-2">
                                                    {{ $item->game->name ?? 'Unknown Game' }}
                                                </h3>
                                                <div class="flex items-center gap-4 text-sm text-slate-400 mb-3">
                                                    <span class="flex items-center gap-1.5">
                                                        <span class="text-yellow-400">⭐</span>
                                                        {{ $item->game->condition ?? 'Unknown' }}
                                                    </span>
                                                    @if($item->game && $item->game->is_for_rent)
                                                        <span class="bg-purple-600/30 text-purple-300 px-3 py-1 rounded-full text-xs font-semibold border border-purple-500/50">
                                                            📅 For Rent
                                                        </span>
                                                    @else
                                                        <span class="bg-emerald-600/30 text-emerald-300 px-3 py-1 rounded-full text-xs font-semibold border border-emerald-500/50">
                                                            💰 For Sale
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <button wire:click="removeFromCart('{{ $item->_id }}')" 
                                                class="text-red-400 hover:text-red-300 p-3 rounded-lg bg-slate-800/50 hover:bg-red-500/20 transition-all duration-200 group">
                                                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        
                                        <!-- Price Display -->
                                        <div>
                                            @if($item->game && $item->game->is_for_rent)
                                                <p class="text-purple-400 font-bold text-lg">
                                                    ${{ number_format($item->game->rent_price, 2) }}<span class="text-sm text-slate-400">/day</span>
                                                </p>
                                            @else
                                                <p class="text-emerald-400 font-bold text-lg">
                                                    ${{ number_format($item->game->sell_price ?? 0, 2) }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Controls Section -->
                            <div class="p-6 bg-slate-800/40">
                                @if($item->game && $item->game->is_for_rent)
                                    <!-- FOR RENTAL ITEMS -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                        <!-- Quantity Control -->
                                        <div>
                                            <label class="block text-sm text-slate-300 mb-3 font-medium">Quantity</label>
                                            <div class="flex items-center bg-slate-700/50 rounded-lg border border-slate-600/50">
                                                <button wire:click="updateQuantity('{{ $item->_id }}', {{ $item->quantity - 1 }})" 
                                                    class="p-3 text-slate-400 hover:text-purple-400 transition-colors {{ $item->quantity <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                    </svg>
                                                </button>
                                                <span class="flex-1 text-center text-white font-semibold text-lg px-4">{{ $item->quantity }}</span>
                                                <button wire:click="updateQuantity('{{ $item->_id }}', {{ $item->quantity + 1 }})" 
                                                    class="p-3 text-slate-400 hover:text-purple-400 transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <!-- Rental Days Control -->
                                        <div>
                                            <label class="block text-sm text-slate-300 mb-3 font-medium">Rental Days</label>
                                            <div class="flex items-center justify-center gap-3">
                                                <button wire:click="updateRentalDays('{{ $item->_id }}', {{ max(1, $item->rental_days - 1) }})" 
                                                    class="bg-slate-700/70 hover:bg-purple-600/70 text-white p-3 rounded-lg transition-colors
                                                           {{ $item->rental_days <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                    {{ $item->rental_days <= 1 ? 'disabled' : '' }}>
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                    </svg>
                                                </button>
                                                
                                                <div class="bg-slate-700/50 border border-slate-600/50 px-6 py-3 rounded-lg text-center min-w-[100px]">
                                                    <span class="text-xl font-bold text-white">{{ $item->rental_days }}</span>
                                                    <div class="text-slate-400 text-xs">{{ $item->rental_days == 1 ? 'day' : 'days' }}</div>
                                                </div>
                                                
                                                <button wire:click="updateRentalDays('{{ $item->_id }}', {{ min(30, $item->rental_days + 1) }})" 
                                                    class="bg-slate-700/70 hover:bg-purple-600/70 text-white p-3 rounded-lg transition-colors
                                                           {{ $item->rental_days >= 30 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                    {{ $item->rental_days >= 30 ? 'disabled' : '' }}>
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                            <div class="text-center mt-2">
                                                <span class="text-purple-400 font-semibold text-sm">
                                                    Total: ${{ number_format($item->game->rent_price * $item->rental_days, 2) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <!-- FOR SALE ITEMS -->
                                    <div class="mb-6">
                                        <label class="block text-sm text-slate-300 mb-3 font-medium">Quantity</label>
                                        <div class="flex items-center bg-slate-700/50 rounded-lg border border-slate-600/50 max-w-xs">
                                            <button wire:click="updateQuantity('{{ $item->_id }}', {{ $item->quantity - 1 }})" 
                                                class="p-3 text-slate-400 hover:text-emerald-400 transition-colors {{ $item->quantity <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                </svg>
                                            </button>
                                            <span class="flex-1 text-center text-white font-semibold text-lg px-4">{{ $item->quantity }}</span>
                                            <button wire:click="updateQuantity('{{ $item->_id }}', {{ $item->quantity + 1 }})" 
                                                class="p-3 text-slate-400 hover:text-emerald-400 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>



                            <!-- Item Total Footer -->
                            <div class="p-6 bg-slate-800/60 border-t border-slate-700/50">
                                <div class="flex justify-between items-center">
                                    <span class="text-slate-300 font-medium text-lg">Item Total:</span>
                                    <span class="{{ ($item->game && $item->game->is_for_rent) ? 'text-purple-300' : 'text-emerald-300' }} font-bold text-2xl">{{ $item->getFormattedTotalPrice() }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Cart Summary Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-slate-800/70 backdrop-blur-xl border border-slate-700/60 rounded-2xl p-8 sticky top-8 shadow-2xl shadow-slate-900/50">
                        <h2 class="text-2xl font-bold text-white mb-8 pb-5 border-b border-slate-700/50 flex items-center gap-3">
                            <span class="text-3xl">🧾</span>
                            Order Summary
                        </h2>
                        
                        <div class="space-y-4 mb-8">
                            <div class="flex justify-between items-center text-slate-300">
                                <span>Items in cart:</span>
                                <span class="text-white font-semibold">{{ $cartCount }}</span>
                            </div>
                            <div class="flex justify-between items-center text-slate-300">
                                <span>Subtotal:</span>
                                <span class="text-white font-semibold">${{ number_format($cartTotal, 2) }}</span>
                            </div>
                        </div>
                        
                        <div class="border-t border-slate-700/50 pt-6 mb-8">
                            <div class="flex justify-between items-center">
                                <span class="text-xl font-semibold text-white">Grand Total:</span>
                                <span class="text-3xl font-extrabold text-emerald-400">${{ number_format($cartTotal, 2) }}</span>
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-r from-blue-500/10 to-purple-500/10 border border-blue-500/20 rounded-lg p-5 mb-8">
                            <div class="flex items-start gap-3">
                                <span class="text-blue-400 text-2xl">💡</span>
                                <div>
                                    <h4 class="text-blue-300 font-semibold text-sm mb-2">Quick Checkout Tip</h4>
                                    <p class="text-blue-300/80 text-xs leading-relaxed">
                                        Use "Buy This Now" or "Rent This Now" on individual items to checkout instantly. Otherwise, use the button below for all items.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <button wire:click="checkout" 
                                class="w-full bg-gradient-to-r from-emerald-500 via-green-500 to-teal-500 hover:from-emerald-600 hover:via-green-600 hover:to-teal-600
                                       text-white font-bold py-4 px-6 rounded-xl text-lg shadow-lg hover:shadow-xl hover:shadow-emerald-500/30
                                       transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                Checkout All Items
                            </button>
                            <button wire:click="continueShopping" 
                                class="w-full bg-slate-700/70 hover:bg-slate-600/80 text-slate-200 hover:text-white
                                       py-4 px-6 rounded-xl font-semibold transition-all duration-300 flex items-center justify-center gap-3 group">
                                <svg class="w-5 h-5 group-hover:animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                Continue Shopping
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Empty Cart -->
        <div class="text-center py-24 max-w-2xl mx-auto">
            <div class="w-36 h-36 bg-gradient-to-br from-slate-700/40 to-slate-800/60 rounded-full flex items-center justify-center mx-auto mb-10 shadow-2xl shadow-slate-900/40 animate-pulse">
                <span class="text-7xl opacity-60">🛒</span>
            </div>
            <h3 class="text-3xl font-bold text-slate-200 mb-6">Your Cart is a Bit Lonely!</h3>
            <p class="text-slate-400 text-lg mb-12 max-w-md mx-auto leading-relaxed">
                Looks like you haven't added any games yet. Explore our collection and find your next adventure!
            </p>
            <button wire:click="continueShopping" 
                class="bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 hover:from-blue-600 hover:via-purple-600 hover:to-pink-600 
                       text-white px-12 py-5 rounded-xl font-bold text-lg shadow-xl hover:shadow-2xl hover:shadow-purple-500/40
                       transform hover:-translate-y-1 transition-all duration-300 inline-flex items-center gap-4 group">
                <span class="group-hover:animate-spin">🎮</span>
                Browse Games
                <span class="transform transition-transform duration-300 group-hover:translate-x-1">🚀</span>
            </button>
        </div>
    @endif
</div>