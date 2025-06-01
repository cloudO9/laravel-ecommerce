<div>
    <!-- Welcome Section - Made More Compact and Dynamic -->
    <div class="gaming-card p-6 rounded-2xl mb-8 relative overflow-hidden border-blue-500/30 hover:border-blue-400/50 transition-all">
        <div class="absolute top-0 right-0 w-40 h-40 bg-blue-500/10 rounded-full blur-3xl -mr-10 -mt-10"></div>
        <div class="absolute bottom-0 left-0 w-40 h-40 bg-cyan-500/10 rounded-full blur-3xl -ml-10 -mb-10"></div>
        
        <div class="relative flex flex-col md:flex-row items-center md:justify-between gap-6 z-10">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 w-16 h-16 bg-gradient-to-r from-blue-600 to-cyan-400 rounded-full flex items-center justify-center shadow-lg shadow-blue-500/20 float-animation">
                    <span class="text-2xl">🎮</span>
                </div>
                <div class="text-center md:text-left">
                    <h1 class="text-2xl font-bold neon-text-blue mb-1">
                        Welcome, {{ auth()->user()->name }}!
                    </h1>
                    <p class="text-gray-300">
                        Discover amazing games from sellers worldwide
                    </p>
                </div>
            </div>
            
            <!-- Quick Stats Inline for Desktop -->
            <div class="flex flex-wrap justify-center md:justify-end gap-4">
                <div class="bg-slate-800/60 backdrop-blur-lg border border-slate-700/50 rounded-xl px-4 py-3 flex items-center gap-3 hover:border-emerald-500/30 transition-all">
                    <div class="w-10 h-10 bg-gradient-to-r from-emerald-500 to-green-500 rounded-lg flex items-center justify-center">
                        <span class="text-base">🎯</span>
                    </div>
                    <div>
                        <div class="text-xl font-bold text-emerald-400">{{ $totalGames }}</div>
                        <div class="text-slate-400 text-xs">Games Available</div>
                    </div>
                </div>
                
                <div class="bg-slate-800/60 backdrop-blur-lg border border-slate-700/50 rounded-xl px-4 py-3 flex items-center gap-3 hover:border-purple-500/30 transition-all">
                    <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg flex items-center justify-center">
                        <span class="text-base">📅</span>
                    </div>
                    <div>
                        <div class="text-xl font-bold text-purple-400">{{ $gamesForRent }}</div>
                        <div class="text-slate-400 text-xs">For Rent</div>
                    </div>
                </div>
                
                <div class="bg-slate-800/60 backdrop-blur-lg border border-slate-700/50 rounded-xl px-4 py-3 flex items-center gap-3 hover:border-yellow-500/30 transition-all">
                    <div class="w-10 h-10 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-lg flex items-center justify-center">
                        <span class="text-base">💰</span>
                    </div>
                    <div>
                        <div class="text-xl font-bold text-yellow-400">{{ $gamesForSale }}</div>
                        <div class="text-slate-400 text-xs">For Sale</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages - Styled to Match Gaming Theme -->
    @if (session()->has('message'))
        <div class="gaming-card border-green-500/30 p-4 rounded-xl mb-6 flex items-center">
            <div class="w-10 h-10 bg-gradient-to-r from-green-600 to-emerald-500 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                <span class="text-xl">✅</span>
            </div>
            <span class="font-medium text-emerald-300">{{ session('message') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="gaming-card border-red-500/30 p-4 rounded-xl mb-6 flex items-center">
            <div class="w-10 h-10 bg-gradient-to-r from-red-600 to-rose-500 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                <span class="text-xl">⚠️</span>
            </div>
            <span class="font-medium text-red-300">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Search & Filter Section - Enhanced with Cleaner Layout -->
    <div class="gaming-card p-6 rounded-2xl mb-8 border-blue-500/20">
        <h2 class="text-xl font-bold neon-text-blue mb-6 flex items-center gap-2">
            <div class="w-8 h-8 bg-gradient-to-r from-blue-600 to-cyan-400 rounded-lg flex items-center justify-center flex-shrink-0">
                <span>🔍</span>
            </div>
            Find Your Perfect Game
        </h2>
        
        <!-- Search Input - Full Width and Prominent -->
        <div class="mb-6">
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="search" 
                    class="w-full pl-12 pr-4 py-3.5 bg-slate-800/60 border border-slate-600/50 rounded-xl text-white text-lg
                           placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500/40
                           transition-all duration-300"
                    placeholder="Search for games by name..." />
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
        
        <!-- Filters - Better Organized with Visual Hierarchy -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Condition Filter -->
            <div>
                <label class="block text-sm font-medium text-blue-300 mb-2 flex items-center">
                    <span class="w-5 h-5 bg-gradient-to-r from-yellow-500 to-orange-500 rounded mr-2 flex items-center justify-center flex-shrink-0">
                        <span class="text-xs">⭐</span>
                    </span>
                    Condition
                </label>
                <select wire:model.live="conditionFilter" 
                    class="w-full px-4 py-3 bg-slate-800/60 border border-slate-600/50 rounded-xl text-white
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
                <label class="block text-sm font-medium text-blue-300 mb-2 flex items-center">
                    <span class="w-5 h-5 bg-gradient-to-r from-purple-500 to-pink-500 rounded mr-2 flex items-center justify-center flex-shrink-0">
                        <span class="text-xs">🏷️</span>
                    </span>
                    Type
                </label>
                <select wire:model.live="typeFilter" 
                    class="w-full px-4 py-3 bg-slate-800/60 border border-slate-600/50 rounded-xl text-white
                           focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500/40
                           transition-all duration-300">
                    <option value="">All Types</option>
                    <option value="sale">💰 For Sale</option>
                    <option value="rent">📅 For Rent</option>
                </select>
            </div>

            <!-- Sort By -->
            <div>
                <label class="block text-sm font-medium text-blue-300 mb-2 flex items-center">
                    <span class="w-5 h-5 bg-gradient-to-r from-indigo-500 to-purple-500 rounded mr-2 flex items-center justify-center flex-shrink-0">
                        <span class="text-xs">📊</span>
                    </span>
                    Sort By
                </label>
                <select wire:model.live="sortBy" 
                    class="w-full px-4 py-3 bg-slate-800/60 border border-slate-600/50 rounded-xl text-white
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

        <!-- Price Range - More Intuitive Layout -->
        <div class="mt-6">
            <label class="block text-sm font-medium text-blue-300 mb-3 flex items-center">
                <span class="w-5 h-5 bg-gradient-to-r from-green-500 to-emerald-500 rounded mr-2 flex items-center justify-center flex-shrink-0">
                    <span class="text-xs">💲</span>
                </span>
                Price Range
            </label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-400">$</span>
                    </div>
                    <input type="number" wire:model.live.debounce.500ms="minPrice" min="0" step="0.01"
                        class="w-full pl-8 pr-4 py-3 bg-slate-800/60 border border-slate-600/50 rounded-xl text-white
                               placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/40
                               transition-all duration-300"
                        placeholder="Min Price" />
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-400">$</span>
                    </div>
                    <input type="number" wire:model.live.debounce.500ms="maxPrice" min="0" step="0.01"
                        class="w-full pl-8 pr-4 py-3 bg-slate-800/60 border border-slate-600/50 rounded-xl text-white
                               placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/40
                               transition-all duration-300"
                        placeholder="Max Price" />
                </div>
            </div>
        </div>

        <!-- Clear Filters Button - More Prominent -->
        @if($search || $conditionFilter || $typeFilter || $minPrice || $maxPrice || $sortBy !== 'newest')
            <div class="mt-6 text-center">
                <button wire:click="clearFilters" 
                    class="bg-blue-600/30 hover:bg-blue-600/50 border border-blue-500/30 text-blue-300 font-medium px-6 py-2.5 rounded-xl 
                           transition-all duration-300 flex items-center gap-2 mx-auto">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Reset All Filters
                </button>
            </div>
        @endif
    </div>

    <!-- Results Header - Better Visual Organization -->
    <div class="gaming-card p-4 rounded-xl mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-slate-700/50">
        <div class="text-slate-300 flex items-center gap-3">
            <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-cyan-400 rounded-lg flex items-center justify-center flex-shrink-0">
                <span class="text-lg">📊</span>
            </div>
            <div>
                <span class="text-2xl font-bold text-white">{{ $games->total() }}</span> 
                <span class="text-slate-400">games found</span>
                @if($search)
                    <span class="text-slate-500">for "{{ $search }}"</span>
                @endif
            </div>
        </div>
        
        <!-- Quick Stats -->
        <div class="flex items-center gap-4 text-sm">
            <span class="flex items-center gap-1 bg-slate-800/60 px-3 py-1.5 rounded-lg border border-emerald-500/20">
                <span class="w-3 h-3 bg-emerald-500 rounded-full"></span>
                <span class="text-emerald-400">{{ $games->where('is_for_rent', false)->count() }}</span> for sale
            </span>
            <span class="flex items-center gap-1 bg-slate-800/60 px-3 py-1.5 rounded-lg border border-purple-500/20">
                <span class="w-3 h-3 bg-purple-500 rounded-full"></span>
                <span class="text-purple-400">{{ $games->where('is_for_rent', true)->count() }}</span> for rent
            </span>
        </div>
    </div>

    <!-- Games Grid - Enhanced Cards -->
    <div wire:loading.remove>
        @if($games->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                @foreach($games as $game)                    <div class="gaming-card rounded-2xl overflow-hidden border-slate-700/50 
                               hover:border-blue-500/50 hover:shadow-xl hover:shadow-blue-500/10 
                               transform hover:-translate-y-2 transition-all duration-300 cursor-pointer group"
                         wire:click="viewGame('{{ $game->_id }}')"
                         wire:key="game-{{ $game->_id }}">
                        
                        <!-- Card Border Glow Effect -->
                        <div class="absolute inset-0 rounded-2xl bg-gradient-to-r from-blue-500/0 via-blue-500/10 to-cyan-500/0 opacity-0 group-hover:opacity-100 -z-10 blur-xl transition-opacity duration-500"></div>
                        
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
                            </div>                            <!-- Hover Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <div class="absolute bottom-4 left-4 right-4">
                                    <button class="w-full bg-blue-600/90 backdrop-blur-sm hover:bg-blue-700/90 text-white px-4 py-2 rounded-lg font-medium
                                                 transition-all duration-200 flex items-center justify-center gap-2 border border-blue-500/50 relative overflow-hidden">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        View Details
                                        <span class="absolute right-2 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0">→</span>
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
                            @endif                            <!-- Price -->
                            <div class="flex items-center justify-between mb-4">
                                <div class="relative">
                                    <div class="text-2xl font-bold bg-gradient-to-r from-emerald-400 to-green-300 bg-clip-text text-transparent">
                                        @if($game->is_for_rent)
                                            ${{ number_format($game->rent_price, 2) }}
                                            <span class="text-sm text-slate-400 font-normal">/day</span>
                                        @else
                                            ${{ number_format($game->sell_price, 2) }}
                                        @endif
                                    </div>
                                    <div class="absolute -bottom-2 left-0 right-0 h-px bg-gradient-to-r from-emerald-500/0 via-emerald-500/30 to-emerald-500/0"></div>
                                </div>
                            </div>

                            <!-- Seller Info -->
                            <div class="pt-4 border-t border-slate-700/50 flex items-center justify-between text-sm">
                                <div class="flex items-center text-slate-400">
                                    <div class="w-6 h-6 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center mr-2 flex-shrink-0">
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

            <!-- Pagination - Enhanced with Gaming Theme -->
            @if($games->hasPages())
                <div class="flex justify-center">
                    <div class="gaming-card p-3 rounded-xl border-blue-500/20 inline-flex">
                        {{ $games->links() }}
                    </div>
                </div>
            @endif
        @else            <!-- No Games Found - Enhanced with Gaming Theme -->
            <div class="gaming-card p-10 rounded-2xl border-slate-700/30 text-center">
                <div class="w-24 h-24 bg-gradient-to-r from-blue-900/30 to-slate-900/30 rounded-3xl flex items-center justify-center mx-auto mb-6 border border-slate-700/50 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-b from-blue-500/5 to-transparent"></div>
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_120%,rgba(0,150,255,0.1),transparent_70%)]"></div>
                    <span class="text-6xl relative">😔</span>
                </div>
                <h3 class="text-2xl font-bold neon-text-blue mb-4">No games found</h3>
                <p class="text-slate-400 mb-8 max-w-md mx-auto">
                    @if($search || $conditionFilter || $typeFilter || $minPrice || $maxPrice)
                        Try adjusting your search filters or check back later for new listings.
                    @else
                        No games have been listed yet. Check back later for amazing deals!
                    @endif
                </p>
                @if($search || $conditionFilter || $typeFilter || $minPrice || $maxPrice)
                    <button wire:click="clearFilters" 
                        class="bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-700 hover:to-cyan-600 text-white px-8 py-3 rounded-xl font-semibold 
                               transition-all duration-300 flex items-center gap-2 mx-auto shadow-lg shadow-blue-500/20 relative overflow-hidden group">
                        <svg class="w-5 h-5 transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        <span>Clear Filters</span>
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                    </button>
                @endif
            </div>
        @endif
    </div>

    <!-- Loading State - Enhanced Animation -->
    <div wire:loading class="gaming-card p-20 rounded-2xl border-blue-500/20 text-center">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-600/20 rounded-2xl mb-4">
            <div class="relative w-12 h-12">
                <div class="absolute top-0 left-0 w-full h-full border-4 border-blue-500/30 rounded-full"></div>
                <div class="absolute top-0 left-0 w-full h-full border-t-4 border-blue-500 rounded-full animate-spin"></div>
            </div>
        </div>
        <p class="text-blue-400">Loading amazing games...</p>
    </div>
</div>