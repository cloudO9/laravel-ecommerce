{{-- resources/views/buyer/purchasehistory.blade.php --}}
<div>
    <!-- Page Header -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-purple-500 to-blue-500 rounded-2xl mb-4 shadow-lg shadow-purple-500/25">
            <span class="text-2xl">📋</span>
        </div>
        <h1 class="text-3xl font-bold bg-gradient-to-r from-purple-400 to-blue-400 bg-clip-text text-transparent mb-2">
            Purchase History
        </h1>
        <p class="text-slate-400">Track all your game purchases and rentals</p>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-slate-800/60 backdrop-blur-lg border border-slate-700/50 rounded-xl p-6 text-center hover:border-purple-500/30 transition-all duration-300">
            <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mx-auto mb-3">
                <span class="text-xl">📋</span>
            </div>
            <div class="text-2xl font-bold text-purple-400 mb-1">{{ $totalOrders }}</div>
            <div class="text-slate-400 text-sm">Total Orders</div>
        </div>
        
        <div class="bg-slate-800/60 backdrop-blur-lg border border-slate-700/50 rounded-xl p-6 text-center hover:border-emerald-500/30 transition-all duration-300">
            <div class="w-12 h-12 bg-gradient-to-r from-emerald-500 to-green-500 rounded-xl flex items-center justify-center mx-auto mb-3">
                <span class="text-xl">💰</span>
            </div>
            <div class="text-2xl font-bold text-emerald-400 mb-1">{{ $totalPurchases }}</div>
            <div class="text-slate-400 text-sm">Purchases</div>
        </div>
        
        <div class="bg-slate-800/60 backdrop-blur-lg border border-slate-700/50 rounded-xl p-6 text-center hover:border-blue-500/30 transition-all duration-300">
            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center mx-auto mb-3">
                <span class="text-xl">📅</span>
            </div>
            <div class="text-2xl font-bold text-blue-400 mb-1">{{ $totalRentals }}</div>
            <div class="text-slate-400 text-sm">Rentals</div>
        </div>

        <div class="bg-slate-800/60 backdrop-blur-lg border border-slate-700/50 rounded-xl p-6 text-center hover:border-yellow-500/30 transition-all duration-300">
            <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center mx-auto mb-3">
                <span class="text-xl">💸</span>
            </div>
            <div class="text-2xl font-bold text-yellow-400 mb-1">${{ number_format($totalSpent, 2) }}</div>
            <div class="text-slate-400 text-sm">Total Spent</div>
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
    <div class="bg-slate-800/60 backdrop-blur-lg border border-slate-700/50 rounded-2xl p-6 mb-8">
        <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
            <span class="text-2xl">🔍</span>
            Search & Filter Orders
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            
            <!-- Search Input -->
            <div>
                <label class="block text-sm font-medium text-slate-200 mb-2">Search Orders</label>
                <div class="relative">
                    <input type="text" wire:model.live.debounce.300ms="search" 
                        class="w-full pl-10 pr-4 py-2.5 bg-slate-900/60 border border-slate-600/50 rounded-lg text-white
                               placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-500/40
                               transition-all duration-300"
                        placeholder="Order number, game name..." />
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-slate-200 mb-2">Status</label>
                <select wire:model.live="statusFilter" 
                    class="w-full px-4 py-2.5 bg-slate-900/60 border border-slate-600/50 rounded-lg text-white
                           focus:outline-none focus:ring-2 focus:ring-purple-500/40 transition-all duration-300">
                    <option value="">All Statuses</option>
                    <option value="pending">⏳ Pending</option>
                    <option value="paid">💳 Paid</option>
                    <option value="shipped">🚛 Shipped</option>
                    <option value="delivered">✅ Delivered</option>
                    <option value="cancelled">❌ Cancelled</option>
                </select>
            </div>

            <!-- Type Filter -->
            <div>
                <label class="block text-sm font-medium text-slate-200 mb-2">Type</label>
                <select wire:model.live="typeFilter" 
                    class="w-full px-4 py-2.5 bg-slate-900/60 border border-slate-600/50 rounded-lg text-white
                           focus:outline-none focus:ring-2 focus:ring-purple-500/40 transition-all duration-300">
                    <option value="">All Types</option>
                    <option value="purchase">💰 Purchases</option>
                    <option value="rental">📅 Rentals</option>
                </select>
            </div>

            <!-- Sort By -->
            <div>
                <label class="block text-sm font-medium text-slate-200 mb-2">Sort By</label>
                <select wire:model.live="sortBy" 
                    class="w-full px-4 py-2.5 bg-slate-900/60 border border-slate-600/50 rounded-lg text-white
                           focus:outline-none focus:ring-2 focus:ring-purple-500/40 transition-all duration-300">
                    <option value="newest">🆕 Newest First</option>
                    <option value="oldest">⏰ Oldest First</option>
                    <option value="amount_high">💰 Highest Amount</option>
                    <option value="amount_low">💵 Lowest Amount</option>
                </select>
            </div>
        </div>

        <!-- Clear Filters Button -->
        @if($search || $statusFilter || $typeFilter || $sortBy !== 'newest')
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
            <span class="text-2xl font-bold text-white">{{ $orders->total() ?? 0 }}</span> 
            <span class="text-slate-400">orders found</span>
            @if($search)
                <span class="text-slate-500">for "{{ $search }}"</span>
            @endif
        </div>
    </div>

    <!-- Orders List -->
    <div wire:loading.remove>
        @if($orders->count() > 0)
            <div class="space-y-6 mb-8">
                @foreach($orders as $order)
                    <div class="bg-slate-800/60 backdrop-blur-lg border border-slate-700/50 rounded-2xl overflow-hidden 
                               hover:border-purple-500/50 hover:shadow-xl hover:shadow-purple-500/10 
                               transition-all duration-300" wire:key="order-{{ $order->_id ?? $loop->index }}">
                        
                        <!-- Order Header -->
                        <div class="bg-slate-900/40 px-6 py-4 border-b border-slate-700/50">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-blue-500 rounded-xl flex items-center justify-center">
                                        <span class="text-xl">{{ $order->type === 'rental' ? '📅' : '💰' }}</span>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-white text-lg">{{ $order->order_number ?? 'N/A' }}</h3>
                                        <p class="text-slate-400 text-sm">
                                            {{ $order->created_at ? $order->created_at->format('M d, Y \a\t g:i A') : 'Date not available' }}
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-4">
                                    <!-- Status Badge -->
                                    @php
                                        $status = $order->status ?? 'pending';
                                        $badgeClass = match($status) {
                                            'paid' => 'bg-green-600/20 text-green-300 border-green-500/40',
                                            'pending' => 'bg-yellow-600/20 text-yellow-300 border-yellow-500/40',
                                            'shipped' => 'bg-blue-600/20 text-blue-300 border-blue-500/40',
                                            'delivered' => 'bg-emerald-600/20 text-emerald-300 border-emerald-500/40',
                                            'cancelled' => 'bg-red-600/20 text-red-300 border-red-500/40',
                                            default => 'bg-slate-600/20 text-slate-300 border-slate-500/40'
                                        };
                                        $statusIcon = match($status) {
                                            'paid' => '💳',
                                            'pending' => '⏳',
                                            'shipped' => '🚛',
                                            'delivered' => '✅',
                                            'cancelled' => '❌',
                                            default => '📋'
                                        };
                                    @endphp
                                    <span class="px-3 py-1.5 rounded-full text-sm font-semibold border {{ $badgeClass }}">
                                        {{ $statusIcon }} {{ ucfirst($status) }}
                                    </span>
                                    
                                    <!-- Order Total -->
                                    <div class="text-right">
                                        <div class="text-2xl font-bold text-emerald-400">${{ number_format($order->total ?? 0, 2) }}</div>
                                        <div class="text-slate-400 text-sm">{{ ucfirst($order->type ?? 'purchase') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Order Items -->
                        <div class="px-6 py-4">
                            <h4 class="font-semibold text-white mb-3 flex items-center gap-2">
                                <span>📦</span>
                                Items ({{ isset($order->items) ? count($order->items) : 0 }})
                            </h4>
                            
                            @if(isset($order->items) && is_array($order->items))
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                                    @foreach($order->items as $item)
                                        <div class="bg-slate-900/40 rounded-lg p-4 border border-slate-700/30">
                                            <div class="flex items-start gap-3">
                                                <div class="w-12 h-12 bg-slate-700/50 rounded-lg flex items-center justify-center flex-shrink-0">
                                                    <span class="text-2xl">🎮</span>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <h5 class="font-semibold text-white text-sm mb-1 truncate">{{ $item['game_name'] ?? 'Unknown Game' }}</h5>
                                                    <div class="space-y-1">
                                                        <p class="text-slate-400 text-xs">Qty: {{ $item['quantity'] ?? 1 }}</p>
                                                        @if(isset($item['rental_days']) && $item['rental_days'] > 1)
                                                            <p class="text-purple-400 text-xs">{{ $item['rental_days'] }} days</p>
                                                        @endif
                                                        <p class="text-emerald-400 font-semibold text-sm">${{ number_format($item['price'] ?? 0, 2) }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Rental Period (for rentals) -->
                            @if(($order->type ?? '') === 'rental' && isset($order->rental_start_date))
                                <div class="bg-blue-500/10 border border-blue-500/20 rounded-lg p-3 mb-4">
                                    <div class="flex items-center gap-2 text-blue-300 text-sm">
                                        <span>📅</span>
                                        <span class="font-semibold">Rental Period:</span>
                                        <span>{{ $order->rental_start_date->format('M d, Y') }}</span>
                                        @if(isset($order->rental_end_date))
                                            <span>to {{ $order->rental_end_date->format('M d, Y') }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Order Actions -->
                        <div class="bg-slate-900/40 px-6 py-4 border-t border-slate-700/50">
                            <div class="flex flex-wrap gap-3">
                                <button wire:click="viewOrder('{{ $order->_id ?? '' }}')" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium
                                           transition-colors duration-200 flex items-center gap-2">
                                    <span>👁️</span>
                                    View Details
                                </button>
                                
                                @if(in_array($order->status ?? '', ['delivered', 'paid']))
                                    <button wire:click="reorder('{{ $order->_id ?? '' }}')" 
                                        class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium
                                               transition-colors duration-200 flex items-center gap-2">
                                        <span>🔄</span>
                                        Reorder
                                    </button>
                                @endif
                                
                                <button wire:click="downloadInvoice('{{ $order->_id ?? '' }}')" 
                                    class="bg-slate-600 hover:bg-slate-700 text-white px-4 py-2 rounded-lg text-sm font-medium
                                           transition-colors duration-200 flex items-center gap-2">
                                    <span>📄</span>
                                    Invoice
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($orders->hasPages())
                <div class="flex justify-center">
                    {{ $orders->links() }}
                </div>
            @endif
        @else
            <!-- No Orders Found -->
            <div class="text-center py-20">
                <div class="w-32 h-32 bg-slate-700/30 rounded-3xl flex items-center justify-center mx-auto mb-6">
                    <span class="text-6xl opacity-50">📋</span>
                </div>
                <h3 class="text-2xl font-bold text-slate-300 mb-4">No orders found</h3>
                <p class="text-slate-400 mb-8 max-w-md mx-auto">
                    @if($search || $statusFilter || $typeFilter)
                        No orders match your current filters. Try adjusting your search criteria.
                    @else
                        You haven't made any purchases or rentals yet. Start browsing games to make your first order!
                    @endif
                </p>
                @if($search || $statusFilter || $typeFilter)
                    <button wire:click="clearFilters" 
                        class="bg-purple-600 hover:bg-purple-700 text-white px-8 py-3 rounded-xl font-semibold 
                               transition-all duration-300 flex items-center gap-2 mx-auto mb-4">
                        <span>🔄</span>
                        Clear Filters
                    </button>
                @endif
                <a href="{{ route('buyer.dashboard') }}" 
                   class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 
                          text-white px-8 py-4 rounded-xl font-bold text-lg
                          transform hover:-translate-y-1 hover:shadow-lg
                          transition-all duration-300 inline-flex items-center gap-3">
                    <span>🎮</span>
                    Browse Games
                    <span>🚀</span>
                </a>
            </div>
        @endif
    </div>

    <!-- Loading State -->
    <div wire:loading class="text-center py-20">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-purple-600/20 rounded-2xl mb-4">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-purple-500"></div>
        </div>
        <p class="text-slate-400">Loading orders...</p>
    </div>
</div>