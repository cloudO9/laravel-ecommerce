<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Users -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path d="M17 6a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path d="M12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07z"></path>
                            <path d="M6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Total Users</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ $totalUsers ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Total Games -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                        <span class="text-white text-sm">🎮</span>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Total Games</h3>
                    <p class="text-2xl font-bold text-green-600">{{ $totalGames ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                        <span class="text-white text-sm">📦</span>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Total Orders</h3>
                    <p class="text-2xl font-bold text-purple-600">{{ $totalOrders ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Revenue -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                        <span class="text-white text-sm">💰</span>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Revenue</h3>
                    <p class="text-2xl font-bold text-yellow-600">${{ number_format($totalRevenue ?? 0, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Users -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Recent Users</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @if(isset($recentUsers) && count($recentUsers) > 0)
                        @foreach($recentUsers as $user)
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                </div>
                                <span class="text-sm text-gray-400">{{ $user->created_at->format('M d') }}</span>
                            </div>
                        @endforeach
                    @else
                        <p class="text-gray-500">No recent users</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Games -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Recent Games</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @if(isset($recentGames) && count($recentGames) > 0)
                        @foreach($recentGames as $game)
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-gray-900">{{ Str::limit($game->name, 30) }}</p>
                                    <p class="text-sm text-gray-500">{{ $game->getFormattedPrice() }}</p>
                                </div>
                                <span class="text-sm text-gray-400">{{ $game->created_at->format('M d') }}</span>
                            </div>
                        @endforeach
                    @else
                        <p class="text-gray-500">No recent games</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>