<div>
    <!-- Welcome Section -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-500 rounded-2xl mb-4 shadow-lg shadow-blue-500/25">
            <span class="text-2xl">🎮</span>
        </div>
        <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent mb-2">
            Welcome, {{ auth()->user()->name }}!
        </h1>
        <p class="text-slate-400 text-lg">
            Discover amazing games from sellers worldwide
        </p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-slate-800/60 backdrop-blur-lg border border-slate-700/50 rounded-xl p-6 text-center hover:border-emerald-500/30 transition-all duration-300">
            <div class="w-12 h-12 bg-gradient-to-r from-emerald-500 to-green-500 rounded-xl flex items-center justify-center mx-auto mb-3">
                <span class="text-xl">🎯</span>
            </div>
            <div class="text-2xl font-bold text-emerald-400 mb-1">{{ $totalGames }}</div>
            <div class="text-slate-400 text-sm">Total Games Available</div>
        </div>
        
        <div class="bg-slate-800/60 backdrop-blur-lg border border-slate-700/50 rounded-xl p-6 text-center hover:border-purple-500/30 transition-all duration-300">
            <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mx-auto mb-3">
                <span class="text-xl">📅</span>
            </div>
            <div class="text-2xl font-bold text-purple-400 mb-1">{{ $gamesForRent }}</div>
            <div class="text-slate-400 text-sm">Available for Rent</div>
        </div>
        
        <div class="bg-slate-800/60 backdrop-blur-lg border border-slate-700/50 rounded-xl p-6 text-center hover:border-yellow-500/30 transition-all duration-300">
            <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center mx-auto mb-3">
                <span class="text-xl">💰</span>
            </div>
            <div class="text-2xl font-bold text-yellow-400 mb-1">{{ $gamesForSale }}</div>
            <div class="text-slate-400 text-sm">Available for Sale</div>
        </div>
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

    <!-- Search & Filter Section -->
    <div class="bg-slate-800/60 backdrop-blur-lg border border-slate-700/50 rounded-2xl p-6 mb-8 shadow-xl">
        <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
            <span class="text-2xl">🔍</span>
            Find Your Perfect Game
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Search Input -->
            <div class="md:col-span-3">
                <label class="block text-sm font-medium text-slate-200 mb-3 flex items-center">
                    <span class="w-5 h-5 bg-gradient-to-r from-emerald-500 to-cyan-500 rounded mr-2 flex items-center justify-center">
                        <span class="text-xs">🔍</span>
                    </span>
                    Search Games
                </label>
                <div class="relative">
                    <input type="text" wire:model.live.debounce.300ms="search" 
                        class="w-full pl-12 pr-4 py-3 bg-slate-900/60 border border-slate-600/50 rounded-xl text-white text-lg
                               placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500/40
                               transition-all duration-300"
                        placeholder="Search for games by name..." />
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    @if($search)
                        <button wire:click="$set('search', '')" 
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    @endif
                </div>
            </div>

            <!-- Condition Filter -->
            <div>
                <label class="block text-sm font-medium text-slate-200 mb-3 flex items-center">
                    <span class="w-5 h-5 bg-gradient-to-r from-yellow-500 to-orange-500 rounded mr-2 flex items-center justify-center">
                        <span class="text-xs">⭐</span>
                    </span>
                    Condition
                </label>
                <select wire:model.live="conditionFilter" 
                    class="w-full px-4 py-3 bg-slate-900/60 border border-slate-600/50 rounded-xl text-white
                           focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500/40
                           transition-all duration-300">
                    <option value="">All Conditions</option>
                    <option value="New">🆕 New</option>
                    <option value="Like New">✨ Like New</option>
                    <option value="Very Good">👍 Very Good</option>
                    <option value="Good">👌 Good</option>
                    <option value="Fair">⚖️ Fair</option>
                    <option value="Poor">📉 Poor</option>
                </select>
            </div>

            <!-- Type Filter -->
            <div>
                <label class="block text-sm font-medium text-slate-200 mb-3 flex items-center">
                    <span class="w-5 h-5 bg-gradient-to-r from-purple-500 to-pink-500 rounded mr-2 flex items-center justify-center">
                        <span class="text-xs">🏷️</span>
                    </span>
                    Type
                </label>
                <select wire:model.live="typeFilter" 
                    class="w-full px-4 py-3 bg-slate-900/60 border border-slate-600/50 rounded-xl text-white
                           focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500/40
                           transition-all duration-300">
                    <option value="">All Types</option>
                    <option value="sale">💰 For Sale</option>
                    <option value="rent">📅 For Rent</option>
                </select>
            </div>

            <!-- Sort By -->
            <div>
                <label class="block text-sm font-medium text-slate-200 mb-3 flex items-center">
                    <span class="w-5 h-5 bg-gradient-to-r from-indigo-500 to-purple-500 rounded mr-2 flex items-center justify-center">
                        <span class="text-xs">📊</span>
                    </span>
                    Sort By
                </label>
                <select wire:model.live="sortBy" 
                    class="w-full px-4 py-3 bg-slate-900/60 border border-slate-600/50 rounded-xl text-white
                           focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500/40
                           transition-all duration-300">
                    <option value="newest">🆕 Newest First</option>
                    <option value="oldest">⏰ Oldest First</option>
                    <option value="price_low">💵 Price: Low to High</option>
                    <option value="price_high">💰 Price: High to Low</option>
                    <option value="name">🔤 Name A-Z</option>
                </select>
            </div>
        </div>

        <!-- Price Range -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-200 mb-2">Min Price ($)</label>
                <input type="number" wire:model.live.debounce.500ms="minPrice" min="0" step="0.01"
                    class="w-full px-4 py-2.5 bg-slate-900/60 border border-slate-600/50 rounded-lg text-white
                           placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/40
                           transition-all duration-300"
                    placeholder="0.00" />
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-200 mb-2">Max Price ($)</label>
                <input type="number" wire:model.live.debounce.500ms="maxPrice" min="0" step="0.01"
                    class="w-full px-4 py-2.5 bg-slate-900/60 border border-slate-600/50 rounded-lg text-white
                           placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/40
                           transition-all duration-300"
                    placeholder="1000.00" />
            </div>
        </div>

        <!-- Clear Filters Button -->
        @if($search || $conditionFilter || $typeFilter || $minPrice || $maxPrice || $sortBy !== 'newest')
            <div class="mt-6 text-center">
                <button wire:click="clearFilters" 
                    class="bg-slate-700/60 hover:bg-slate-600/60 text-slate-200 px-6 py-2.5 rounded-lg 
                           transition-all duration-300 flex items-center gap-2 mx-auto">
                    <span>🗑️</span>
                    Clear All Filters
                </button>
            </div>
        @endif
    </div>

    <!-- Results Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div class="text-slate-300">
            <span class="text-2xl font-bold text-white">{{ $games->total() }}</span> 
            <span class="text-slate-400">games found</span>
            @if($search)
                <span class="text-slate-500">for "{{ $search }}"</span>
            @endif
        </div>
        
        <!-- Quick Stats -->
        <div class="flex items-center gap-4 text-sm text-slate-400">
            <span class="flex items-center gap-1">
                <span class="w-3 h-3 bg-emerald-500 rounded-full"></span>
                {{ $games->where('is_for_rent', false)->count() }} for sale
            </span>
            <span class="flex items-center gap-1">
                <span class="w-3 h-3 bg-purple-500 rounded-full"></span>
                {{ $games->where('is_for_rent', true)->count() }} for rent
            </span>
        </div>
    </div>

    <!-- Games Grid -->
    <div wire:loading.remove>
        @if($games->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                @foreach($games as $game)
                    <div class="bg-slate-800/60 backdrop-blur-lg border border-slate-700/50 rounded-2xl overflow-hidden 
                               hover:border-blue-500/50 hover:shadow-xl hover:shadow-blue-500/10 
                               transform hover:-translate-y-2 transition-all duration-300 cursor-pointer group"
                         wire:click="viewGame('{{ $game->_id }}')"
                         wire:key="game-{{ $game->_id }}">
                        
                        <!-- Game Image -->
                        <div class="aspect-video bg-slate-700/50 relative overflow-hidden">
                            @if($game->image)
                                <img src="{{ Storage::url($game->image) }}" alt="{{ $game->name }}" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-700 to-slate-800">
                                    <span class="text-6xl opacity-30">🎮</span>
                                </div>
                            @endif
                            
                            <!-- Type Badge -->
                            <div class="absolute top-3 left-3">
                                @if($game->is_for_rent)
                                    <span class="bg-purple-600/90 backdrop-blur-sm text-white px-3 py-1.5 rounded-full text-xs font-semibold flex items-center gap-1">
                                        <span>📅</span> For Rent
                                    </span>
                                @else
                                    <span class="bg-emerald-600/90 backdrop-blur-sm text-white px-3 py-1.5 rounded-full text-xs font-semibold flex items-center gap-1">
                                        <span>💰</span> For Sale
                                    </span>
                                @endif
                            </div>

                            <!-- Condition Badge -->
                            <div class="absolute top-3 right-3">
                                <span class="bg-slate-900/80 backdrop-blur-sm text-white px-2 py-1 rounded-full text-xs font-medium">
                                    {{ $game->condition }}
                                </span>
                            </div>

                            <!-- Hover Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <div class="absolute bottom-4 left-4 right-4">
                                    <button class="w-full bg-blue-600/90 backdrop-blur-sm hover:bg-blue-700/90 text-white px-4 py-2 rounded-lg font-medium
                                                 transition-all duration-200 flex items-center justify-center gap-2">
                                        <span>👁️</span>
                                        View Details
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Game Info -->
                        <div class="p-5">
                            <h3 class="font-bold text-white text-lg mb-2 line-clamp-1 group-hover:text-blue-400 transition-colors">
                                {{ $game->name }}
                            </h3>
                            
                            @if($game->description)
                                <p class="text-slate-400 text-sm mb-4 line-clamp-2 leading-relaxed">
                                    {{ $game->description }}
                                </p>
                            @endif

                            <!-- Price -->
                            <div class="flex items-center justify-between mb-4">
                                <div class="text-2xl font-bold text-emerald-400">
                                    @if($game->is_for_rent)
                                        ${{ number_format($game->rent_price, 2) }}
                                        <span class="text-sm text-slate-400 font-normal">/day</span>
                                    @else
                                        ${{ number_format($game->sell_price, 2) }}
                                    @endif
                                </div>
                            </div>

                            <!-- Seller Info -->
                            <div class="pt-4 border-t border-slate-700/50 flex items-center justify-between text-sm">
                                <div class="flex items-center text-slate-400">
                                    <div class="w-6 h-6 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center mr-2">
                                        <span class="text-xs">👤</span>
                                    </div>
                                    <span>{{ $game->seller->name ?? 'Unknown Seller' }}</span>
                                </div>
                                <span class="text-slate-500 text-xs">{{ $game->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($games->hasPages())
                <div class="flex justify-center">
                    {{ $games->links() }}
                </div>
            @endif
        @else
            <!-- No Games Found -->
            <div class="text-center py-20">
                <div class="w-32 h-32 bg-slate-700/30 rounded-3xl flex items-center justify-center mx-auto mb-6">
                    <span class="text-6xl opacity-50">😔</span>
                </div>
                <h3 class="text-2xl font-bold text-slate-300 mb-4">No games found</h3>
                <p class="text-slate-400 mb-8 max-w-md mx-auto">
                    @if($search || $conditionFilter || $typeFilter || $minPrice || $maxPrice)
                        Try adjusting your search filters or check back later for new listings.
                    @else
                        No games have been listed yet. Check back later for amazing deals!
                    @endif
                </p>
                @if($search || $conditionFilter || $typeFilter || $minPrice || $maxPrice)
                    <button wire:click="clearFilters" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-semibold 
                               transition-all duration-300 flex items-center gap-2 mx-auto">
                        <span>🔄</span>
                        Clear Filters
                    </button>
                @endif
            </div>
        @endif
    </div>

    <!-- Loading State -->
    <div wire:loading class="text-center py-20">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600/20 rounded-2xl mb-4">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
        </div>
        <p class="text-slate-400">Loading games...</p>
    </div>
</div>