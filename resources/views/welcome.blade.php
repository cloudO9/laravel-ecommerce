<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GameHub - Gaming CD Marketplace</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .gradient-bg {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        }
        .glass-effect {
            background: rgba(30, 41, 59, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(71, 85, 105, 0.3);
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>
<body class="gradient-bg text-white min-h-screen">
    
    <!-- Header -->
    <header class="relative z-10 px-6 py-6">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-r from-emerald-500 to-cyan-500 rounded-xl flex items-center justify-center">
                    <span class="text-2xl">💿</span>
                </div>
                <div>
                    <h1 class="text-2xl font-bold bg-gradient-to-r from-emerald-400 to-cyan-400 bg-clip-text text-transparent">
                        GameHub
                    </h1>
                    <p class="text-slate-400 text-sm">Gaming CD Marketplace</p>
                </div>
            </div>

            <!-- Auth Buttons -->
            @if (Route::has('login'))
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ url('/dashboard') }}" 
                           class="px-4 py-2 text-slate-300 hover:text-emerald-400 transition-colors duration-200">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                           class="px-4 py-2 text-slate-300 hover:text-white transition-colors duration-200">
                            Log in
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" 
                               class="px-6 py-2 bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-700 hover:to-emerald-600 rounded-lg font-medium transition-all duration-200">
                                Register
                            </a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative z-10 px-6 py-16 text-center">
        <div class="max-w-4xl mx-auto">
            <div class="w-24 h-24 bg-gradient-to-r from-purple-500 via-emerald-500 to-cyan-500 rounded-full mx-auto mb-6 flex items-center justify-center">
                <span class="text-4xl">💿</span>
            </div>
            
            <h2 class="text-5xl md:text-6xl font-bold mb-6">
                <span class="bg-gradient-to-r from-emerald-400 via-cyan-400 to-purple-400 bg-clip-text text-transparent">
                    Gaming CD Hub
                </span>
            </h2>
            
            <p class="text-xl md:text-2xl text-slate-300 mb-8">
                Buy, sell & rent gaming CDs with fellow gamers
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-12">
                <button class="px-8 py-4 bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-700 hover:to-emerald-600 rounded-xl font-semibold transition-all duration-200 flex items-center gap-2">
                    <span>💿</span> Browse CDs
                </button>
                <button class="px-8 py-4 glass-effect rounded-xl font-semibold transition-all duration-200 hover:bg-slate-700/50">
                    <span>📈</span> Sell Your CDs
                </button>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="relative z-10 px-6 py-16">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12">
                <h3 class="text-3xl font-bold mb-4">
                    <span class="bg-gradient-to-r from-emerald-400 to-cyan-400 bg-clip-text text-transparent">
                        Why GameHub?
                    </span>
                </h3>
                <p class="text-slate-400">The easiest way to trade gaming CDs</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Buy CDs -->
                <div class="glass-effect rounded-2xl p-8 transition-all duration-300 card-hover">
                    <div class="w-16 h-16 bg-gradient-to-r from-emerald-500 to-green-500 rounded-xl flex items-center justify-center mb-6">
                        <span class="text-2xl">💰</span>
                    </div>
                    <h4 class="text-xl font-bold text-white mb-4">Buy CDs</h4>
                    <p class="text-slate-400 mb-4">
                        Find rare and popular gaming CDs at great prices from trusted sellers.
                    </p>
                    <div class="text-emerald-400 font-medium">Browse Collection →</div>
                </div>

                <!-- Sell CDs -->
                <div class="glass-effect rounded-2xl p-8 transition-all duration-300 card-hover">
                    <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-indigo-500 rounded-xl flex items-center justify-center mb-6">
                        <span class="text-2xl">📈</span>
                    </div>
                    <h4 class="text-xl font-bold text-white mb-4">Sell CDs</h4>
                    <p class="text-slate-400 mb-4">
                        Turn your gaming CD collection into cash. Easy listing and secure payments.
                    </p>
                    <div class="text-purple-400 font-medium">Start Selling →</div>
                </div>

                <!-- Rent CDs -->
                <div class="glass-effect rounded-2xl p-8 transition-all duration-300 card-hover">
                    <div class="w-16 h-16 bg-gradient-to-r from-cyan-500 to-blue-500 rounded-xl flex items-center justify-center mb-6">
                        <span class="text-2xl">📅</span>
                    </div>
                    <h4 class="text-xl font-bold text-white mb-4">Rent CDs</h4>
                    <p class="text-slate-400 mb-4">
                        Try games before buying. Rent CDs for days or weeks at affordable rates.
                    </p>
                    <div class="text-cyan-400 font-medium">Rent Now →</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Categories -->
    <section class="relative z-10 px-6 py-16">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12">
                <h3 class="text-3xl font-bold mb-4 text-white">Popular Categories</h3>
                <p class="text-slate-400">Find CDs by game type</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="glass-effect rounded-xl p-6 text-center transition-all duration-200 hover:bg-slate-700/50">
                    <div class="text-3xl mb-3">🎯</div>
                    <div class="text-white font-medium">Action</div>
                    <div class="text-slate-400 text-sm">250+ CDs</div>
                </div>
                <div class="glass-effect rounded-xl p-6 text-center transition-all duration-200 hover:bg-slate-700/50">
                    <div class="text-3xl mb-3">🏎️</div>
                    <div class="text-white font-medium">Racing</div>
                    <div class="text-slate-400 text-sm">180+ CDs</div>
                </div>
                <div class="glass-effect rounded-xl p-6 text-center transition-all duration-200 hover:bg-slate-700/50">
                    <div class="text-3xl mb-3">⚔️</div>
                    <div class="text-white font-medium">RPG</div>
                    <div class="text-slate-400 text-sm">320+ CDs</div>
                </div>
                <div class="glass-effect rounded-xl p-6 text-center transition-all duration-200 hover:bg-slate-700/50">
                    <div class="text-3xl mb-3">⚽</div>
                    <div class="text-white font-medium">Sports</div>
                    <div class="text-slate-400 text-sm">140+ CDs</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="relative z-10 px-6 py-12 border-t border-slate-700/50">
        <div class="max-w-6xl mx-auto text-center">
            <div class="flex items-center justify-center gap-3 mb-4">
                <div class="w-10 h-10 bg-gradient-to-r from-emerald-500 to-cyan-500 rounded-lg flex items-center justify-center">
                    <span class="text-xl">💿</span>
                </div>
                <span class="text-xl font-bold bg-gradient-to-r from-emerald-400 to-cyan-400 bg-clip-text text-transparent">
                    GameHub
                </span>
            </div>
            <p class="text-slate-400 mb-6">
                The trusted marketplace for gaming CD enthusiasts
            </p>
            <p class="text-slate-500 text-sm">
                © 2024 GameHub. Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
            </p>
        </div>
    </footer>
</body>
</html>