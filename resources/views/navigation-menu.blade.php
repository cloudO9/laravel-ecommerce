<!-- resources/views/livewire/navigation-menu.blade.php -->
<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 relative z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="gaming-logo">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-400 via-cyan-400 to-blue-600 rounded-lg flex items-center justify-center shadow-lg shadow-blue-500/25 hover:shadow-blue-500/40 transition-all duration-300">
                            <span class="text-2xl">🎮</span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    @auth
                        @if(auth()->user()->role === 'buyer')
                            <x-nav-link href="{{ route('buyer.dashboard') }}" :active="request()->routeIs('buyer.dashboard')">
                                🎮 Browse Games
                            </x-nav-link>
                            
                            <x-nav-link href="{{ route('buyer.purchase-history') }}" :active="request()->routeIs('buyer.purchase-history')">
                                📋 Purchase History
                            </x-nav-link>
                            
                            <x-nav-link href="{{ route('buyer.cart') }}" :active="request()->routeIs('buyer.cart')">
                                🛒 Cart
                            </x-nav-link>
                        @elseif(auth()->user()->role === 'seller')
                            <x-nav-link href="{{ route('seller.dashboard') }}" :active="request()->routeIs('seller.dashboard')">
                                🏠 Dashboard
                            </x-nav-link>
                            
                            <x-nav-link href="{{ route('seller.manage-games') }}" :active="request()->routeIs('seller.manage-games')">
                                🎮 Manage Games
                            </x-nav-link>
                            
                            <x-nav-link href="{{ route('seller.sales-activity') }}" :active="request()->routeIs('seller.sales-activity')">
                                📊 Sales & Analytics
                            </x-nav-link>
                        @endif
                    @endauth
                    
                    @guest
                        <!-- Links for non-authenticated users -->
                        <x-nav-link href="{{ route('login') }}">
                            🔑 Login
                        </x-nav-link>
                        <x-nav-link href="{{ route('register') }}">
                            📝 Register
                        </x-nav-link>
                    @endguest
                </div>
            </div>

            @auth
                <div class="hidden sm:flex sm:items-center sm:ml-8">
                    <!-- User Info and Actions -->
                    <div class="flex items-center space-x-6">
                        <!-- Profile Icon -->
                        <a href="{{ route('profile.show') }}" class="flex items-center justify-center w-10 h-10 bg-gradient-to-r from-blue-500 to-cyan-400 rounded-full hover:from-blue-600 hover:to-cyan-500 transition-all duration-300 hover:shadow-lg hover:shadow-blue-500/25">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <img class="w-8 h-8 rounded-full object-cover border-2 border-white/20" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                            @else
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            @endif
                        </a>
                        
                        <!-- Username -->
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 px-2">
                            {{ Auth::user()->name }}
                        </span>
                        
                        <!-- Logout Button -->
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="flex items-center justify-center px-4 py-2 text-sm font-medium text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all duration-200 ml-2">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            @endauth

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        @auth
            <div class="pt-2 pb-3 space-y-1">
                @if(auth()->user()->role === 'buyer')
                    <x-responsive-nav-link href="{{ route('buyer.dashboard') }}" :active="request()->routeIs('buyer.dashboard')">
                        🎮 Browse Games
                    </x-responsive-nav-link>
                    
                    <x-responsive-nav-link href="{{ route('buyer.purchase-history') }}" :active="request()->routeIs('buyer.purchase-history')">
                        📋 Purchase History
                    </x-responsive-nav-link>
                    
                    <x-responsive-nav-link href="{{ route('buyer.cart') }}" :active="request()->routeIs('buyer.cart')">
                        🛒 Cart
                    </x-responsive-nav-link>
                @elseif(auth()->user()->role === 'seller')
                    <x-responsive-nav-link href="{{ route('seller.dashboard') }}" :active="request()->routeIs('seller.dashboard')">
                        🏠 Dashboard
                    </x-responsive-nav-link>
                    
                    <x-responsive-nav-link href="{{ route('seller.manage-games') }}" :active="request()->routeIs('seller.manage-games')">
                        🎮 Manage Games
                    </x-responsive-nav-link>
                    
                    <x-responsive-nav-link href="{{ route('seller.sales-activity') }}" :active="request()->routeIs('seller.sales-activity')">
                        📊 Sales & Analytics
                    </x-responsive-nav-link>
                @endif
            </div>

            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                <div class="flex items-center px-4 justify-between">
                    <div class="flex items-center space-x-4">
                        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                            <div class="shrink-0">
                                <img class="h-10 w-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                            </div>
                        @else
                            <div class="shrink-0">
                                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-cyan-400 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            </div>
                        @endif

                        <div>
                            <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                            <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                        </div>
                    </div>
                    
                    <!-- Mobile Logout Button -->
                    <form method="POST" action="{{ route('logout') }}" class="inline ml-4">
                        @csrf
                        <button type="submit" class="flex items-center justify-center px-3 py-2 text-sm font-medium text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        </button>
                    </form>
                </div>

                <div class="mt-3 space-y-1">
                    <!-- Account Management -->
                    <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            {{ __('Profile') }}
                        </div>
                    </x-responsive-nav-link>
                </div>
            </div>
        @endauth

        @guest
            <!-- Guest mobile navigation -->
            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link href="{{ route('login') }}">
                    🔑 Login
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('register') }}">
                    📝 Register
                </x-responsive-nav-link>
            </div>
        @endguest
    </div>
</nav>