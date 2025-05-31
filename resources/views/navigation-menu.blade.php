<!-- resources/views/livewire/navigation-menu.blade.php -->
<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="gaming-logo">
                        <div class="w-10 h-10 bg-gradient-to-r from-green-400 to-green-600 rounded-lg flex items-center justify-center">
                            <span class="text-xl">🎮</span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    @auth
                        @if(auth()->user()->role === 'buyer')
                            <x-nav-link href="{{ route('buyer.dashboard') }}" :active="request()->routeIs('buyer.dashboard')">
                                🏠 Dashboard
                            </x-nav-link>
                            
                            <x-nav-link href="{{ route('buyer.browse-games') }}" :active="request()->routeIs('buyer.browse-games')">
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
                <div class="hidden sm:flex sm:items-center sm:ml-6">
                    <!-- Settings Dropdown -->
                    <div class="ml-3 relative">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                    <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                        <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                    </button>
                                @else
                                    <span class="inline-flex rounded-md">
                                        <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150">
                                            {{ Auth::user()->name }}

                                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        </button>
                                    </span>
                                @endif
                            </x-slot>

                            <x-slot name="content">
                                <!-- Account Management -->
                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    {{ __('Manage Account') }}
                                </div>

                                <x-dropdown-link href="{{ route('profile.show') }}">
                                    {{ __('Profile') }}
                                </x-dropdown-link>

                                <div class="border-t border-gray-200 dark:border-gray-600"></div>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}" x-data>
                                    @csrf

                                    <x-dropdown-link href="{{ route('logout') }}"
                                             @click.prevent="$root.submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
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
                        🏠 Dashboard
                    </x-responsive-nav-link>
                    
                    <x-responsive-nav-link href="{{ route('buyer.browse-games') }}" :active="request()->routeIs('buyer.browse-games')">
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
                <div class="flex items-center px-4">
                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                        <div class="shrink-0 mr-3">
                            <img class="h-10 w-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                        </div>
                    @endif

                    <div>
                        <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <!-- Account Management -->
                    <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}" x-data>
                        @csrf

                        <x-responsive-nav-link href="{{ route('logout') }}"
                                       @click.prevent="$root.submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
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