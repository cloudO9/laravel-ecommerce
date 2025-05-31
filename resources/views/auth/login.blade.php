<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Login</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        }
        .glass-effect {
            background: rgba(30, 41, 59, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(71, 85, 105, 0.3);
        }
        .input-glow:focus {
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.3);
        }
        .floating {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <!-- Background Pattern -->
    <div class="fixed inset-0 opacity-10">
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.15) 1px, transparent 0); background-size: 50px 50px;"></div>
    </div>

    <!-- Login Card -->
    <div class="w-full max-w-md relative z-10">
        <!-- Logo Section -->
        <div class="text-center mb-8 floating">
            <div class="w-20 h-20 bg-gradient-to-r from-emerald-500 to-cyan-500 rounded-2xl mx-auto mb-4 flex items-center justify-center">
                <span class="text-3xl">💿</span>
            </div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-emerald-400 to-cyan-400 bg-clip-text text-transparent mb-2">
                GameHub
            </h1>
            <p class="text-slate-400">Welcome back, gamer!</p>
        </div>

        <!-- Login Form -->
        <div class="glass-effect rounded-2xl p-8 shadow-2xl">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-white mb-2">Sign In</h2>
                <p class="text-slate-400 text-sm">Access your gaming marketplace</p>
            </div>

            <!-- Validation Errors -->
            <div class="mb-4" id="validation-errors" style="display: none;">
                <div class="bg-red-500/20 border border-red-500/40 text-red-300 px-4 py-3 rounded-lg text-sm">
                    <span class="mr-2">⚠️</span>
                    <span id="error-messages"></span>
                </div>
            </div>

            <!-- Status Message -->
            <div class="mb-4" id="status-message" style="display: none;">
                <div class="bg-emerald-500/20 border border-emerald-500/40 text-emerald-300 px-4 py-3 rounded-lg text-sm">
                    <span class="mr-2">✅</span>
                    <span id="status-text"></span>
                </div>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email Field -->
                <div>
                    <label for="email" class="flex items-center text-sm font-medium text-slate-200 mb-2">
                        <span class="w-5 h-5 bg-gradient-to-r from-emerald-500 to-green-500 rounded text-xs flex items-center justify-center mr-2">📧</span>
                        Email Address
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           autofocus 
                           autocomplete="username"
                           class="w-full px-4 py-3 bg-slate-900/60 border border-slate-600/50 rounded-lg text-white
                                  placeholder-slate-400 text-sm input-glow
                                  focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500/40
                                  transition-all duration-200" 
                           placeholder="Enter your email..." />
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="flex items-center text-sm font-medium text-slate-200 mb-2">
                        <span class="w-5 h-5 bg-gradient-to-r from-purple-500 to-indigo-500 rounded text-xs flex items-center justify-center mr-2">🔒</span>
                        Password
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           required 
                           autocomplete="current-password"
                           class="w-full px-4 py-3 bg-slate-900/60 border border-slate-600/50 rounded-lg text-white
                                  placeholder-slate-400 text-sm input-glow
                                  focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500/40
                                  transition-all duration-200" 
                           placeholder="Enter your password..." />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <label for="remember_me" class="flex items-center cursor-pointer">
                        <input type="checkbox" 
                               id="remember_me" 
                               name="remember"
                               class="w-4 h-4 text-emerald-600 bg-slate-900/60 border-slate-600/50 rounded 
                                      focus:ring-emerald-500/40 focus:ring-2" />
                        <span class="ml-2 text-sm text-slate-300">Remember me</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" 
                           class="text-sm text-emerald-400 hover:text-emerald-300 transition-colors duration-200">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <!-- Submit Button -->
                <button type="submit"
                        class="w-full bg-gradient-to-r from-emerald-600 to-emerald-500 
                               hover:from-emerald-700 hover:to-emerald-600
                               text-white font-semibold py-3 px-6 rounded-lg
                               transform hover:-translate-y-0.5 hover:shadow-lg hover:shadow-emerald-500/20
                               transition-all duration-200 
                               focus:outline-none focus:ring-2 focus:ring-emerald-500/40
                               flex items-center justify-center gap-2">
                    <span>🎮</span>
                    Sign In
                    <span>→</span>
                </button>
            </form>

            <!-- Register Link -->
            @if (Route::has('register'))
                <div class="mt-6 text-center">
                    <p class="text-slate-400 text-sm">
                        Don't have an account? 
                        <a href="{{ route('register') }}" 
                           class="text-emerald-400 hover:text-emerald-300 font-medium transition-colors duration-200">
                            Sign up here
                        </a>
                    </p>
                </div>
            @endif
        </div>

        <!-- Back to Home -->
        <div class="text-center mt-6">
            <a href="{{ url('/') }}" 
               class="text-slate-400 hover:text-emerald-400 text-sm transition-colors duration-200 flex items-center justify-center gap-2">
                <span>←</span> Back to GameHub
            </a>
        </div>
    </div>

    <!-- Demo script for status/error messages -->
    <script>
        // This would be handled by Laravel Blade in actual implementation
        document.addEventListener('DOMContentLoaded', function() {
            // Example of how errors would be displayed
            // @if ($errors->any())
            //     document.getElementById('validation-errors').style.display = 'block';
            //     document.getElementById('error-messages').textContent = '{{ $errors->first() }}';
            // @endif
            
            // @if (session('status'))
            //     document.getElementById('status-message').style.display = 'block';
            //     document.getElementById('status-text').textContent = '{{ session('status') }}';
            // @endif
        });
    </script>
</body>
</html>