<div>
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-slate-900/40 rounded-xl p-4 text-center border border-slate-700/30">
            <div class="w-8 h-8 bg-gradient-to-r from-emerald-500 to-green-500 rounded-lg flex items-center justify-center mx-auto mb-2">
                <span class="text-sm">💰</span>
            </div>
            <div class="text-xl font-bold text-emerald-400">${{ number_format($totalRevenue, 2) }}</div>
            <div class="text-slate-400 text-xs">Total Revenue</div>
        </div>
        
        <div class="bg-slate-900/40 rounded-xl p-4 text-center border border-slate-700/30">
            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-lg flex items-center justify-center mx-auto mb-2">
                <span class="text-sm">🛒</span>
            </div>
            <div class="text-xl font-bold text-blue-400">{{ $totalSales }}</div>
            <div class="text-slate-400 text-xs">Games Sold</div>
        </div>
        
        <div class="bg-slate-900/40 rounded-xl p-4 text-center border border-slate-700/30">
            <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-indigo-500 rounded-lg flex items-center justify-center mx-auto mb-2">
                <span class="text-sm">📅</span>
            </div>
            <div class="text-xl font-bold text-purple-400">{{ $totalRentals }}</div>
            <div class="text-slate-400 text-xs">Games Rented</div>
        </div>

        <div class="bg-slate-900/40 rounded-xl p-4 text-center border border-slate-700/30">
            <div class="w-8 h-8 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-lg flex items-center justify-center mx-auto mb-2">
                <span class="text-sm">📋</span>
            </div>
            <div class="text-xl font-bold text-yellow-400">{{ $recentOrders }}</div>
            <div class="text-slate-400 text-xs">Recent Orders</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-slate-900/40 rounded-xl p-4 mb-6 border border-slate-700/30">
        <h3 class="text-white font-medium mb-3 flex items-center gap-2">
            <span class="text-lg">🔍</span>
            Filter Orders
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
            <!-- Date Range -->
            <div>
                <label class="block text-xs font-medium text-slate-300 mb-1">Time Period</label>
                <select wire:model.live="dateRange" 
                    class="w-full px-3 py-2 bg-slate-800 border border-slate-600/50 rounded text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/40">
                    <option value="7">Last 7 days</option>
                    <option value="30">Last 30 days</option>
                    <option value="90">Last 3 months</option>
                    <option value="all">All time</option>
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-xs font-medium text-slate-300 mb-1">Status</label>
                <select wire:model.live="statusFilter" 
                    class="w-full px-3 py-2 bg-slate-800 border border-slate-600/50 rounded text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/40">
                    <option value="">All Statuses</option>
                    <option value="paid">💳 Paid</option>
                    <option value="shipped">🚛 Shipped</option>
                    <option value="delivered">✅ Delivered</option>
                    <option value="cancelled">❌ Cancelled</option>
                </select>
            </div>

            <!-- Type Filter -->
            <div>
                <label class="block text-xs font-medium text-slate-300 mb-1">Type</label>
                <select wire:model.live="typeFilter" 
                    class="w-full px-3 py-2 bg-slate-800 border border-slate-600/50 rounded text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/40">
                    <option value="">All Types</option>
                    <option value="purchase">💰 Purchases</option>
                    <option value="rental">📅 Rentals</option>
                </select>
            </div>

            <!-- Game Filter -->
            <div>
                <label class="block text-xs font-medium text-slate-300 mb-1">Game</label>
                <select wire:model.live="gameFilter" 
                    class="w-full px-3 py-2 bg-slate-800 border border-slate-600/50 rounded text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/40">
                    <option value="">All Games</option>
                    @foreach($myGames as $game)
                        <option value="{{ $game['_id'] }}">{{ $game['name'] }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Clear Filters -->
            <div class="flex items-end">
                <button wire:click="clearFilters" 
                    class="w-full bg-slate-700 hover:bg-slate-600 text-slate-200 px-3 py-2 rounded text-sm transition-colors flex items-center justify-center gap-1">
                    <span>🗑️</span>
                    Clear
                </button>
            </div>
        </div>
    </div>

    <!-- Orders List -->
    <div class="space-y-4">
        @if($orders->count() > 0)
            @foreach($orders as $order)
                @php
                    $myItems = $this->getMyItemsFromOrder($order);
                    $myRevenue = $this->getMyRevenueFromOrder($order);
                @endphp
                
                @if(!empty($myItems))
                <div class="bg-slate-900/40 rounded-xl p-4 border border-slate-700/30 hover:border-emerald-500/30 transition-colors" wire:key="order-{{ $order->_id }}">
                    <!-- Order Header -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-lg flex items-center justify-center">
                                <span class="text-lg">{{ $order->type === 'rental' ? '📅' : '💰' }}</span>
                            </div>
                            <div>
                                <h4 class="font-semibold text-white">{{ $order->order_number }}</h4>
                                <p class="text-slate-400 text-sm">{{ $order->created_at->format('M d, Y g:i A') }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <!-- Status Badge -->
                            @php
                                $status = $order->status ?? 'pending';
                                $badgeClass = match($status) {
                                    'paid' => 'bg-green-600/20 text-green-300 border-green-500/40',
                                    'shipped' => 'bg-blue-600/20 text-blue-300 border-blue-500/40',
                                    'delivered' => 'bg-emerald-600/20 text-emerald-300 border-emerald-500/40',
                                    'cancelled' => 'bg-red-600/20 text-red-300 border-red-500/40',
                                    default => 'bg-yellow-600/20 text-yellow-300 border-yellow-500/40'
                                };
                                $statusIcon = match($status) {
                                    'paid' => '💳',
                                    'shipped' => '🚛',
                                    'delivered' => '✅',
                                    'cancelled' => '❌',
                                    default => '⏳'
                                };
                            @endphp
                            <span class="px-3 py-1 rounded-full text-sm font-medium border {{ $badgeClass }}">
                                {{ $statusIcon }} {{ ucfirst($status) }}
                            </span>
                            
                            <!-- My Revenue -->
                            <div class="text-right">
                                <div class="text-xl font-bold text-emerald-400">${{ number_format($myRevenue, 2) }}</div>
                                <div class="text-slate-400 text-xs">Your Revenue</div>
                            </div>
                        </div>
                    </div>

                    <!-- My Items from this Order -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                        @foreach($myItems as $item)
                            <div class="bg-slate-800/40 rounded-lg p-3 border border-slate-700/20">
                                <div class="flex items-start gap-3">
                                    <div class="w-12 h-12 bg-slate-700/50 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <span class="text-lg">🎮</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h5 class="font-medium text-white text-sm mb-1 truncate">{{ $item['game_name'] ?? 'Unknown Game' }}</h5>
                                        <div class="space-y-1">
                                            <p class="text-slate-400 text-xs">
                                                <span class="inline-flex items-center gap-1">
                                                    {{ $item['type'] === 'rental' ? '📅 Rented' : '💰 Sold' }}
                                                    • Qty: {{ $item['quantity'] ?? 1 }}
                                                </span>
                                                @if($item['type'] === 'rental' && isset($item['rental_days']))
                                                    <span class="block mt-1">⏰ {{ $item['rental_days'] }} days</span>
                                                @endif
                                            </p>
                                            <div class="flex justify-between items-center">
                                                <span class="text-emerald-400 font-semibold text-sm">${{ number_format($item['total_price'] ?? 0, 2) }}</span>
                                                <span class="text-xs text-slate-500">
                                                    ${{ number_format($item['unit_price'] ?? 0, 2) }}{{ $item['type'] === 'rental' ? '/day' : '' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Customer & Shipping Info -->
                    <div class="pt-3 border-t border-slate-700/30">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <!-- Customer Contact Info -->
                            <div class="space-y-2">
                                <h4 class="text-slate-300 font-medium mb-2">Customer Info</h4>
                                <div class="space-y-1 text-slate-400">
                                    <div class="flex items-center gap-2">
                                        <span>👤</span>
                                        <span>{{ ($order->shipping_address['first_name'] ?? '') . ' ' . ($order->shipping_address['last_name'] ?? '') }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span>📧</span>
                                        <span>{{ $order->contact_info['email'] ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span>📱</span>
                                        <span>{{ $order->contact_info['phone'] ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Shipping Address -->
                            <div class="space-y-2">
                                <h4 class="text-slate-300 font-medium mb-2">Shipping Address</h4>
                                <div class="text-slate-400 text-sm">
                                    @if(isset($order->shipping_address) && !empty($order->shipping_address))
                                        <div class="space-y-1">
                                            <div>{{ $order->shipping_address['address'] ?? '' }}</div>
                                            <div>
                                                {{ $order->shipping_address['city'] ?? '' }}
                                                @if($order->shipping_address['state'] ?? '')
                                                    , {{ $order->shipping_address['state'] }}
                                                @endif
                                                {{ $order->shipping_address['zip_code'] ?? '' }}
                                            </div>
                                            <div>{{ $order->shipping_address['country'] ?? '' }}</div>
                                        </div>
                                    @else
                                        <span class="text-slate-500">No shipping address provided</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Order Summary -->
                        <div class="mt-4 pt-3 border-t border-slate-700/30 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 text-sm">
                            <div class="text-slate-500 text-xs">
                                Order Type: {{ ucfirst($order->type) }} • 
                                Order #{{ $order->order_number }} • 
                                {{ $order->created_at->format('M d, Y g:i A') }}
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            @endforeach

            <!-- Pagination -->
            @if($orders->hasPages())
                <div class="flex justify-center mt-6">
                    {{ $orders->links() }}
                </div>
            @endif
        @else
            <!-- No Orders -->
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-slate-700/30 rounded-3xl flex items-center justify-center mx-auto mb-4">
                    <span class="text-4xl opacity-50">📊</span>
                </div>
                <h3 class="text-xl font-bold text-slate-300 mb-2">No sales or rentals found</h3>
                <p class="text-slate-400 text-sm mb-4">
                    @if($statusFilter || $typeFilter || $gameFilter)
                        No orders match your current filters. Try adjusting your search criteria.
                    @else
                        Your game sales and rentals will appear here once buyers start purchasing.
                    @endif
                </p>
                @if($statusFilter || $typeFilter || $gameFilter)
                    <button wire:click="clearFilters" 
                        class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2 mx-auto">
                        <span>🔄</span>
                        Clear Filters
                    </button>
                @endif
            </div>
        @endif
    </div>

    <!-- Loading State -->
    <div wire:loading class="text-center py-8">
        <div class="inline-flex items-center justify-center w-12 h-12 bg-emerald-600/20 rounded-xl mb-3">
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-emerald-500"></div>
        </div>
        <p class="text-slate-400 text-sm">Loading sales data...</p>
    </div>
</div>