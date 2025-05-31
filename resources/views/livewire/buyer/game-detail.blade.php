<div>
    @if($game)
        <!-- Back Button -->
        <div class="mb-6">
            <button wire:click="goBack" 
                class="flex items-center gap-2 text-slate-400 hover:text-white transition-colors duration-200 bg-slate-800/60 px-4 py-2 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                <span>Back to Games</span>
            </button>
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

        <!-- Game Detail Card -->
        <div class="bg-slate-800/60 backdrop-blur-lg border border-slate-700/50 rounded-2xl overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
                
                <!-- Game Image Section -->
                <div class="relative">
                    <div class="aspect-square lg:aspect-auto lg:h-96 bg-slate-700/50 relative overflow-hidden">
                        @if($game->image)
                            <img src="{{ Storage::url($game->image) }}" alt="{{ $game->name }}" 
                                 class="w-full h-full object-cover" />
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-700 to-slate-800">
                                <span class="text-8xl opacity-30">🎮</span>
                            </div>
                        @endif
                        
                        <!-- Type Badge -->
                        <div class="absolute top-4 left-4">
                            @if($game->is_for_rent)
                                <div class="bg-purple-600/90 backdrop-blur-sm text-white px-4 py-2 rounded-xl font-semibold flex items-center gap-2">
                                    <span>📅</span>
                                    <span>For Rent</span>
                                </div>
                            @else
                                <div class="bg-emerald-600/90 backdrop-blur-sm text-white px-4 py-2 rounded-xl font-semibold flex items-center gap-2">
                                    <span>💰</span>
                                    <span>For Sale</span>
                                </div>
                            @endif
                        </div>

                        <!-- Condition Badge -->
                        <div class="absolute top-4 right-4">
                            <div class="bg-slate-900/80 backdrop-blur-sm text-white px-4 py-2 rounded-xl font-medium">
                                <span class="text-yellow-400">⭐</span> {{ $game->condition }}
                            </div>
                        </div>

                        <!-- Availability Status Badge -->
                        <div class="absolute bottom-4 right-4">
                            <div class="px-3 py-1 rounded-full text-sm font-semibold
                                {{ $game->getStatusColor() }} 
                                @if($game->isAvailable()) bg-green-600/20
                                @elseif($game->isRented()) bg-yellow-600/20
                                @else bg-red-600/20
                                @endif">
                                {{ $game->getStatusDisplay() }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Game Info Section -->
                <div class="p-8">
                    <!-- Game Title -->
                    <div class="mb-6">
                        <h1 class="text-3xl font-bold text-white mb-3">{{ $game->name }}</h1>
                        @if($game->description)
                            <p class="text-slate-300 text-lg leading-relaxed">{{ $game->description }}</p>
                        @endif
                    </div>

                    <!-- Game Details -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                        <!-- Condition -->
                        <div class="bg-slate-900/50 rounded-xl p-4">
                            <div class="text-slate-400 text-sm mb-1">Condition</div>
                            <div class="text-white font-semibold">{{ $game->condition }}</div>
                        </div>

                        <!-- Listed Date -->
                        <div class="bg-slate-900/50 rounded-xl p-4">
                            <div class="text-slate-400 text-sm mb-1">Listed</div>
                            <div class="text-white font-semibold">{{ $game->created_at->diffForHumans() }}</div>
                        </div>

                        <!-- Availability -->
                        <div class="bg-slate-900/50 rounded-xl p-4">
                            <div class="text-slate-400 text-sm mb-1">Status</div>
                            <div class="font-semibold {{ $game->getStatusColor() }}">{{ $game->getStatusDisplay() }}</div>
                        </div>

                        <!-- Type -->
                        <div class="bg-slate-900/50 rounded-xl p-4">
                            <div class="text-slate-400 text-sm mb-1">Type</div>
                            <div class="text-white font-semibold">{{ $game->getTypeDisplay() }}</div>
                        </div>
                    </div>

                    <!-- Seller Information -->
                    @if($seller)
                        <div class="bg-slate-900/50 rounded-xl p-4 mb-6">
                            <h3 class="text-white font-semibold mb-3 flex items-center gap-2">
                                <span>👤</span>
                                Seller Information
                            </h3>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-r from-emerald-500 to-cyan-500 rounded-xl flex items-center justify-center">
                                        <span class="font-bold text-white">{{ substr($seller->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="text-white font-semibold">{{ $seller->name }}</p>
                                        <p class="text-slate-400 text-sm">{{ $seller->email }}</p>
                                    </div>
                                </div>
                                <button wire:click="contactSeller" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium
                                           transition-colors duration-200 flex items-center gap-2">
                                    <span>💬</span>
                                    Contact
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- Price and Action Section -->
                    <div class="bg-gradient-to-r from-slate-900/80 to-slate-800/80 rounded-xl p-6 border border-slate-700/50">
                        <div class="mb-6">
                            <!-- Price Display -->
                            <div class="text-center mb-6">
                                <div class="text-4xl font-bold text-emerald-400 mb-2">
                                    @if($game->is_for_rent)
                                        ${{ number_format($game->rent_price, 2) }}
                                        <span class="text-xl text-slate-400 font-normal">/day</span>
                                    @else
                                        ${{ number_format($game->sell_price, 2) }}
                                    @endif
                                </div>
                                <p class="text-slate-400 text-lg">
                                    @if($game->is_for_rent)
                                        Daily rental price
                                    @else
                                        One-time purchase price
                                    @endif
                                </p>
                            </div>

                            <!-- Rental Days Selector (for rent games only) -->
                            @if($game->is_for_rent)
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-slate-200 mb-3 text-center">
                                        How many days would you like to rent?
                                    </label>
                                    <div class="flex items-center justify-center gap-4">
                                        <button wire:click="decreaseRentalDays"
                                            class="bg-slate-700/60 hover:bg-slate-600/60 text-white p-3 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                            </svg>
                                        </button>
                                        <div class="bg-slate-800/60 px-6 py-3 rounded-lg min-w-[100px] text-center">
                                            <span class="text-2xl font-bold text-white">{{ $rentalDays ?? 1 }}</span>
                                            <span class="text-slate-400 text-sm block">{{ ($rentalDays ?? 1) == 1 ? 'day' : 'days' }}</span>
                                        </div>
                                        <button wire:click="increaseRentalDays"
                                            class="bg-slate-700/60 hover:bg-slate-600/60 text-white p-3 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="text-center mt-3">
                                        <span class="text-slate-400 text-sm">Total: </span>
                                        <span class="text-emerald-400 font-bold text-lg">
                                            ${{ number_format($game->rent_price * ($rentalDays ?? 1), 2) }}
                                        </span>
                                    </div>
                                </div>
                            @endif

                            <!-- Action Buttons -->
                            @if($game->seller_id !== auth()->id())
                                @if($game->isAvailable())
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <!-- Add to Cart Button -->
                                        <button wire:click="addToCart" 
                                            class="bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600
                                                   text-white font-bold py-4 px-6 rounded-xl text-lg
                                                   transform hover:-translate-y-1 hover:shadow-lg hover:shadow-blue-500/25
                                                   transition-all duration-300 flex items-center justify-center gap-3">
                                            <span class="text-xl">🛒</span>
                                            Add to Cart
                                        </button>

                                        <!-- Buy Now / Rent Now Button -->
                                        @if($game->is_for_rent)
                                            <button wire:click="rentGame" 
                                                class="bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 
                                                       text-white font-bold py-4 px-6 rounded-xl text-lg
                                                       transform hover:-translate-y-1 hover:shadow-lg hover:shadow-purple-500/25
                                                       transition-all duration-300 flex items-center justify-center gap-3">
                                                <span class="text-xl">📅</span>
                                                Rent Now
                                            </button>
                                        @else
                                            <button wire:click="buyGame" 
                                                class="bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 
                                                       text-white font-bold py-4 px-6 rounded-xl text-lg
                                                       transform hover:-translate-y-1 hover:shadow-lg hover:shadow-emerald-500/25
                                                       transition-all duration-300 flex items-center justify-center gap-3">
                                                <span class="text-xl">💰</span>
                                                Buy Now
                                            </button>
                                        @endif
                                    </div>
                                @else
                                    <!-- Unavailable Game Message -->
                                    <div class="text-center py-8">
                                        <div class="bg-slate-700/30 rounded-xl p-6 border border-slate-600/30">
                                            <span class="text-4xl mb-3 block">
                                                @if($game->isSold()) 
                                                    ❌ 
                                                @elseif($game->isRented()) 
                                                    📅 
                                                @else 
                                                    ⚠️ 
                                                @endif
                                            </span>
                                            <h3 class="text-xl font-bold text-slate-300 mb-2">
                                                @if($game->isSold()) 
                                                    Game Already Sold
                                                @elseif($game->isRented()) 
                                                    Currently Rented
                                                @else 
                                                    Not Available
                                                @endif
                                            </h3>
                                            <p class="text-slate-400">
                                                @if($game->isSold()) 
                                                    This game has been purchased by another buyer.
                                                @elseif($game->isRented()) 
                                                    This game is currently rented by another user.
                                                @else 
                                                    This game is temporarily unavailable.
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            @else
                                <!-- Own Game Message -->
                                <div class="text-center py-8">
                                    <div class="bg-slate-700/30 rounded-xl p-6 border border-slate-600/30">
                                        <span class="text-4xl mb-3 block">👤</span>
                                        <h3 class="text-xl font-bold text-slate-300 mb-2">This is your game</h3>
                                        <p class="text-slate-400">You cannot purchase or add your own game to cart.</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Quick Cart Link -->
                            @if($game->seller_id !== auth()->id() && $game->isAvailable())
                                <div class="mt-6 pt-6 border-t border-slate-700/50 text-center">
                                    <p class="text-slate-400 text-sm mb-2">Want to browse more games first?</p>
                                    <div class="flex items-center justify-center gap-4">
                                        <a href="{{ route('buyer.dashboard') }}" 
                                           class="text-blue-400 hover:text-blue-300 text-sm font-medium transition-colors">
                                            ← Continue Shopping
                                        </a>
                                        <span class="text-slate-600">|</span>
                                        <a href="{{ route('buyer.cart') }}" 
                                           class="text-emerald-400 hover:text-emerald-300 text-sm font-medium transition-colors">
                                            View Cart →
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Game Information -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Game Features -->
            <div class="bg-slate-800/60 backdrop-blur-lg border border-slate-700/50 rounded-2xl p-6">
                <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                    <span>🎯</span>
                    Game Features
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400">Condition</span>
                        <span class="text-white font-medium">{{ $game->condition }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400">Availability</span>
                        <span class="font-medium {{ $game->getStatusColor() }}">{{ $game->getStatusDisplay() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400">Listed Date</span>
                        <span class="text-white font-medium">{{ $game->created_at->format('M d, Y') }}</span>
                    </div>
                    @if($game->is_for_rent)
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400">Max Rental</span>
                            <span class="text-white font-medium">30 days</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Purchase/Rental Info -->
            <div class="bg-slate-800/60 backdrop-blur-lg border border-slate-700/50 rounded-2xl p-6">
                <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                    <span>💡</span>
                    {{ $game->is_for_rent ? 'Rental' : 'Purchase' }} Info
                </h3>
                <div class="space-y-3">
                    @if($game->is_for_rent)
                        <div class="flex items-center gap-2 text-slate-300">
                            <span class="text-green-400">✓</span>
                            <span class="text-sm">Flexible rental periods (1-30 days)</span>
                        </div>
                        <div class="flex items-center gap-2 text-slate-300">
                            <span class="text-green-400">✓</span>
                            <span class="text-sm">Contact seller for pickup/delivery</span>
                        </div>
                        <div class="flex items-center gap-2 text-slate-300">
                            <span class="text-green-400">✓</span>
                            <span class="text-sm">Return in original condition</span>
                        </div>
                        <div class="flex items-center gap-2 text-slate-300">
                            <span class="text-green-400">✓</span>
                            <span class="text-sm">Secure payment via Stripe</span>
                        </div>
                    @else
                        <div class="flex items-center gap-2 text-slate-300">
                            <span class="text-green-400">✓</span>
                            <span class="text-sm">One-time purchase, yours forever</span>
                        </div>
                        <div class="flex items-center gap-2 text-slate-300">
                            <span class="text-green-400">✓</span>
                            <span class="text-sm">Physical delivery to your address</span>
                        </div>
                        <div class="flex items-center gap-2 text-slate-300">
                            <span class="text-green-400">✓</span>
                            <span class="text-sm">Condition: {{ $game->condition }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-slate-300">
                            <span class="text-green-400">✓</span>
                            <span class="text-sm">Secure payment via Stripe</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    @else
        <!-- Game Not Found -->
        <div class="text-center py-20">
            <div class="w-24 h-24 bg-slate-700/50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <span class="text-4xl opacity-50">😔</span>
            </div>
            <h3 class="text-xl font-semibold text-slate-300 mb-2">Game not found</h3>
            <p class="text-slate-400 mb-6">The game you're looking for doesn't exist or has been removed.</p>
            <button wire:click="goBack" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                Back to Games
            </button>
        </div>
    @endif
</div>