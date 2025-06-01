<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Register</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            overflow-x: hidden;
            background: #000;
        }
        
        /* Enhanced Dark Background with Intense Multiple Glow Layers */
        .gaming-bg {
            background: 
                radial-gradient(circle at 25% 25%, #1a0a2e 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, #16213e 0%, transparent 50%),
                radial-gradient(circle at 50% 0%, #0f1419 0%, transparent 50%),
                linear-gradient(180deg, #000000 0%, #0a0a0a 50%, #000000 100%);
            position: relative;
            min-height: 100vh;
        }
        
        /* Animated Background Glow Effects */
        .gaming-bg::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 30%, rgba(255, 0, 255, 0.25) 0%, transparent 40%),
                radial-gradient(circle at 80% 20%, rgba(0, 255, 255, 0.2) 0%, transparent 40%),
                radial-gradient(circle at 40% 70%, rgba(147, 51, 234, 0.3) 0%, transparent 40%),
                radial-gradient(circle at 60% 40%, rgba(168, 85, 247, 0.15) 0%, transparent 50%);
            pointer-events: none;
            z-index: 1;
            animation: backgroundPulse 8s ease-in-out infinite alternate;
        }
        
        @keyframes backgroundPulse {
            0% { opacity: 0.7; }
            100% { opacity: 1; }
        }
        
                
        /* Enhanced Glass Effect with Stronger Glow */
        .glass-card {
            background: rgba(5, 5, 15, 0.8);
            backdrop-filter: blur(25px);
            border: 2px solid;
            border-image: linear-gradient(135deg, 
                rgba(255, 0, 255, 0.5) 0%, 
                rgba(0, 255, 255, 0.3) 50%, 
                rgba(147, 51, 234, 0.5) 100%) 1;
            box-shadow: 
                0 15px 35px rgba(255, 0, 255, 0.2),
                0 5px 15px rgba(0, 255, 255, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.1),
                0 0 50px rgba(147, 51, 234, 0.1);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 
                0 25px 50px rgba(255, 0, 255, 0.3),
                0 0 50px rgba(0, 255, 255, 0.2),
                0 0 100px rgba(147, 51, 234, 0.15);
            border-image: linear-gradient(135deg, 
                rgba(255, 0, 255, 0.8) 0%, 
                rgba(0, 255, 255, 0.6) 50%, 
                rgba(255, 0, 255, 0.8) 100%) 1;
        }
        
        /* Neon Text Effects */
        .neon-text {
            color: #ffffff;
            text-shadow: 
                0 0 5px rgba(255, 0, 255, 0.4),
                0 0 10px rgba(255, 0, 255, 0.3),
                0 0 15px rgba(255, 0, 255, 0.2);
        }
        
        .neon-text-cyan {
            color: #ffffff;
            text-shadow: 
                0 0 5px rgba(0, 255, 255, 0.5),
                0 0 10px rgba(0, 255, 255, 0.3),
                0 0 15px rgba(0, 255, 255, 0.2);
        }
        
        .gaming-gradient-text {
            background: linear-gradient(135deg, #ff00ff 0%, #8b5cf6 25%, #00ffff 50%, #9333ea 75%, #ff00ff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            background-size: 200% 200%;
            animation: gradientShift 4s ease-in-out infinite;
            filter: drop-shadow(0 0 5px rgba(255, 0, 255, 0.3)) drop-shadow(0 0 8px rgba(0, 255, 255, 0.2));
        }
        
        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        
        /* Enhanced Input Styles */
        .gaming-input {
            background: rgba(5, 5, 15, 0.9) !important;
            border: 2px solid rgba(255, 0, 255, 0.3) !important;
            color: #fff !important;
            transition: all 0.3s ease;
        }
        
        .gaming-input:focus {
            border-color: rgba(255, 0, 255, 0.7) !important;
            box-shadow: 
                0 0 20px rgba(255, 0, 255, 0.4),
                0 0 40px rgba(0, 255, 255, 0.2) !important;
            outline: none !important;
            transform: scale(1.02);
        }
        
        .gaming-input::placeholder {
            color: rgba(255, 255, 255, 0.4) !important;
        }
        
        /* Gaming Button with Intense Effects */
        .gaming-button {
            background: linear-gradient(135deg, #ff00ff, #00ffff) !important;
            border: none;
            color: #000 !important;
            font-weight: 600;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .gaming-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.5s;
        }
        
        .gaming-button:hover::before {
            left: 100%;
        }
        
        .gaming-button:hover {
            box-shadow: 
                0 0 20px rgba(255, 0, 255, 0.8),
                0 0 40px rgba(0, 255, 255, 0.6),
                0 0 60px rgba(255, 0, 255, 0.4);
            transform: scale(1.05) translateY(-2px);
            color: #000 !important;
        }
        
        /* Gaming Links */
        .gaming-link {
            color: #ff00ff !important;
            text-shadow: 0 0 5px rgba(255, 0, 255, 0.3);
            transition: all 0.3s ease;
        }
        
        .gaming-link:hover {
            color: #00ffff !important;
            text-shadow: 
                0 0 5px #00ffff,
                0 0 10px #00ffff,
                0 0 15px #00ffff;
        }
        
        /* Floating Animation Enhanced */
        .floating-glow {
            animation: enhancedFloat 4s ease-in-out infinite;
        }
        
        @keyframes enhancedFloat {
            0%, 100% { 
                transform: translateY(0px) rotate(0deg);
                filter: drop-shadow(0 10px 20px rgba(255, 0, 255, 0.3));
            }
            25% { 
                transform: translateY(-10px) rotate(1deg);
                filter: drop-shadow(0 15px 30px rgba(0, 255, 255, 0.4));
            }
            50% { 
                transform: translateY(-20px) rotate(0deg);
                filter: drop-shadow(0 20px 40px rgba(255, 0, 255, 0.5));
            }
            75% { 
                transform: translateY(-10px) rotate(-1deg);
                filter: drop-shadow(0 15px 30px rgba(0, 255, 255, 0.4));
            }
        }
        
        .pulse-rainbow {
            animation: pulseRainbow 3s ease-in-out infinite;
        }
        
        @keyframes pulseRainbow {
            0%, 100% { 
                box-shadow: 0 0 30px rgba(255, 0, 255, 0.6);
            }
            33% { 
                box-shadow: 0 0 40px rgba(0, 255, 255, 0.8);
            }
            66% { 
                box-shadow: 0 0 50px rgba(147, 51, 234, 0.7);
            }
        }
        
        /* Orbital Rings Animation */
        .orbital-rings {
            position: absolute;
            width: 120px;
            height: 120px;
            border: 2px solid rgba(255, 0, 255, 0.3);
            border-radius: 50%;
            animation: orbit 10s linear infinite;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
        }
        
        .orbital-rings::before {
            content: '';
            position: absolute;
            top: -15px;
            left: -15px;
            right: -15px;
            bottom: -15px;
            border: 1px solid rgba(0, 255, 255, 0.2);
            border-radius: 50%;
            animation: orbit 15s linear infinite reverse;
        }
        
        @keyframes orbit {
            0% { transform: translateX(-50%) rotate(0deg); }
            100% { transform: translateX(-50%) rotate(360deg); }
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.3);
        }
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #ff00ff, #00ffff);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(255, 0, 255, 0.5);
        }
        
        /* Background Pattern */
        .pattern-overlay {
            background-image: radial-gradient(circle at 2px 2px, rgba(255, 0, 255, 0.15) 1px, transparent 0);
            background-size: 30px 30px;
            opacity: 0.3;
        }
    </style>
</head>
<body class="gaming-bg min-h-screen flex items-center justify-center p-4">
    <!-- Enhanced Background Pattern -->
    <div class="fixed inset-0 pattern-overlay z-0"></div>

    <!-- Register Card -->
    <div class="w-full max-w-md relative z-10">
        <!-- Logo Section with Enhanced Effects -->
        <div class="text-center mb-8 floating-glow relative">
            <!-- Orbital Rings -->
            <div class="orbital-rings"></div>
            
            <div class="w-24 h-24 mx-auto mb-6 relative pulse-rainbow rounded-2xl flex items-center justify-center" 
                 style="background: linear-gradient(135deg, #ff00ff, #00ffff);">
                <span class="text-4xl">🎮</span>
            </div>
            <h1 class="text-4xl font-bold gaming-gradient-text mb-3">
                GameHub
            </h1>
            <p class="neon-text-cyan text-lg">Join the gaming community!</p>
        </div>

        <!-- Register Form with Glass Card -->
        <div class="glass-card rounded-2xl p-8 shadow-2xl">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold neon-text mb-3">Create Account</h2>
                <p class="neon-text-cyan text-base">Start your gaming journey</p>
            </div>

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="mb-4">
                    <div class="bg-red-500/20 border border-red-500/40 text-red-300 px-4 py-3 rounded-lg text-sm">
                        <span class="mr-2">⚠️</span>
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <!-- Name Field -->
                <div>
                    <label for="name" class="flex items-center text-sm font-medium neon-text mb-3">
                        <span class="w-6 h-6 rounded text-sm flex items-center justify-center mr-3" 
                              style="background: linear-gradient(135deg, #9333ea, #8b5cf6);">👤</span>
                        Full Name
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}"
                           required 
                           autofocus 
                           autocomplete="name"
                           class="gaming-input w-full px-4 py-3 rounded-lg text-white
                                  placeholder-slate-400 text-sm
                                  focus:outline-none transition-all duration-200" 
                           placeholder="Enter your full name..." />
                </div>

                <!-- Email Field -->
                <div>
                    <label for="email" class="flex items-center text-sm font-medium neon-text mb-3">
                        <span class="w-6 h-6 rounded text-sm flex items-center justify-center mr-3" 
                              style="background: linear-gradient(135deg, #06b6d4, #0891b2);">📧</span>
                        Email Address
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}"
                           required 
                           autocomplete="username"
                           class="gaming-input w-full px-4 py-3 rounded-lg text-white
                                  placeholder-slate-400 text-sm
                                  focus:outline-none transition-all duration-200" 
                           placeholder="Enter your email..." />
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="flex items-center text-sm font-medium neon-text mb-3">
                        <span class="w-6 h-6 rounded text-sm flex items-center justify-center mr-3" 
                              style="background: linear-gradient(135deg, #9333ea, #8b5cf6);">🔒</span>
                        Password
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           required 
                           autocomplete="new-password"
                           class="gaming-input w-full px-4 py-3 rounded-lg text-white
                                  placeholder-slate-400 text-sm
                                  focus:outline-none transition-all duration-200" 
                           placeholder="Create a password..." />
                </div>

                <!-- Confirm Password Field -->
                <div>
                    <label for="password_confirmation" class="flex items-center text-sm font-medium neon-text mb-3">
                        <span class="w-6 h-6 rounded text-sm flex items-center justify-center mr-3" 
                              style="background: linear-gradient(135deg, #f59e0b, #d97706);">🔑</span>
                        Confirm Password
                    </label>
                    <input type="password" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           required 
                           autocomplete="new-password"
                           class="gaming-input w-full px-4 py-3 rounded-lg text-white
                                  placeholder-slate-400 text-sm
                                  focus:outline-none transition-all duration-200" 
                           placeholder="Confirm your password..." />
                </div>

                <!-- Role Selection -->
                <div>
                    <label class="flex items-center text-sm font-medium neon-text mb-3">
                        <span class="w-6 h-6 rounded text-sm flex items-center justify-center mr-3" 
                              style="background: linear-gradient(135deg, #ec4899, #db2777);">🎯</span>
                        Register as
                    </label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="relative cursor-pointer">
                            <input type="radio" name="role" value="buyer" checked class="sr-only" />
                            <div class="gaming-input p-4 rounded-lg text-center transition-all duration-200 hover:scale-105 border-2">
                                <div class="text-2xl mb-2">🛒</div>
                                <div class="font-semibold neon-text">Buyer</div>
                                <div class="text-xs text-slate-400">Purchase CDs</div>
                            </div>
                        </label>
                        <label class="relative cursor-pointer">
                            <input type="radio" name="role" value="seller" class="sr-only" />
                            <div class="gaming-input p-4 rounded-lg text-center transition-all duration-200 hover:scale-105 border-2">
                                <div class="text-2xl mb-2">💰</div>
                                <div class="font-semibold neon-text">Seller</div>
                                <div class="text-xs text-slate-400">Sell CDs</div>
                            </div>
                        </label>
                    </div>
                </div>

                @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                    <!-- Terms and Privacy -->
                    <div>
                        <label for="terms" class="flex items-start cursor-pointer">
                            <input type="checkbox" 
                                   name="terms" 
                                   id="terms" 
                                   required
                                   class="w-4 h-4 text-emerald-600 bg-slate-900/60 border-slate-600/50 rounded 
                                          focus:ring-emerald-500/40 focus:ring-2 mt-1 mr-3" />
                            <div class="text-sm neon-text-cyan">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="gaming-link underline transition-colors duration-200">'.__('Terms of Service').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="gaming-link underline transition-colors duration-200">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </label>
                    </div>
                @endif

                <!-- Submit Button -->
                <button type="submit"
                        class="gaming-button w-full font-semibold py-3 px-6 rounded-lg
                               transition-all duration-200 
                               focus:outline-none
                               flex items-center justify-center gap-2">
                    <span>🚀</span>
                    Create Account
                    <span>🎮</span>
                </button>
            </form>

            <!-- Login Link -->
            @if (Route::has('login'))
                <div class="mt-6 text-center">
                    <p class="text-slate-400 text-sm">
                        Already have an account? 
                        <a href="{{ route('login') }}" 
                           class="gaming-link font-medium transition-colors duration-200">
                            Sign in here
                        </a>
                    </p>
                </div>
            @endif
        </div>

        <!-- Back to Home -->
        <div class="text-center mt-6">
            <a href="{{ url('/') }}" 
               class="gaming-link text-sm transition-colors duration-200 flex items-center justify-center gap-2">
                <span>←</span> Back to GameHub
            </a>
        </div>
    </div>

    <!-- Demo script for role selection -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleInputs = document.querySelectorAll('input[name="role"]');
            const roleCards = document.querySelectorAll('input[name="role"] + div');
            
            function updateSelection() {
                roleInputs.forEach((input, index) => {
                    const card = roleCards[index];
                    if (input.checked) {
                        card.style.borderColor = 'rgba(255, 0, 255, 0.8)';
                        card.style.boxShadow = '0 0 20px rgba(255, 0, 255, 0.4)';
                        card.style.background = 'rgba(255, 0, 255, 0.1)';
                    } else {
                        card.style.borderColor = 'rgba(255, 0, 255, 0.3)';
                        card.style.boxShadow = 'none';
                        card.style.background = 'rgba(5, 5, 15, 0.9)';
                    }
                });
            }
            
            roleInputs.forEach(input => {
                input.addEventListener('change', updateSelection);
            });
            
            updateSelection(); // Initial state
        });
    </script>
</body>
</html>