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
        
        /* Scrollbar Styling */
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
        }
        
        /* Subtle Neon Text Effects */
        .neon-text {
            color: #ffffff;
            text-shadow: 
                0 0 5px rgba(255, 0, 255, 0.3),
                0 0 10px rgba(255, 0, 255, 0.2),
                0 0 15px rgba(255, 0, 255, 0.1);
        }
        
        .neon-text-cyan {
            color: #ffffff;
            text-shadow: 
                0 0 5px rgba(0, 255, 255, 0.4),
                0 0 10px rgba(0, 255, 255, 0.2),
                0 0 15px rgba(0, 255, 255, 0.1);
        }
        
        .neon-text-rainbow {
            background: linear-gradient(45deg, #ff00ff, #00ffff, #ff00ff, #00ffff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: rainbowShift 3s ease-in-out infinite;
            filter: drop-shadow(0 0 3px rgba(255, 0, 255, 0.3)) drop-shadow(0 0 6px rgba(0, 255, 255, 0.2));
        }
        
        @keyframes neonFlicker {
            0%, 100% { 
                text-shadow: 
                    0 0 3px rgba(255, 0, 255, 0.3),
                    0 0 6px rgba(255, 0, 255, 0.2),
                    0 0 9px rgba(255, 0, 255, 0.1);
            }
            50% { 
                text-shadow: 
                    0 0 2px rgba(255, 0, 255, 0.2),
                    0 0 4px rgba(255, 0, 255, 0.15),
                    0 0 6px rgba(255, 0, 255, 0.1);
            }
        }
        
        @keyframes rainbowShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        /* Enhanced Card Hover Effects */
        .card-hover {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
        }
        
        .card-hover:hover {
            transform: translateY(-15px) scale(1.05);
            box-shadow: 
                0 25px 50px rgba(255, 0, 255, 0.4),
                0 0 50px rgba(0, 255, 255, 0.3),
                0 0 100px rgba(147, 51, 234, 0.2);
            border-image: linear-gradient(135deg, 
                rgba(255, 0, 255, 0.8) 0%, 
                rgba(0, 255, 255, 0.6) 50%, 
                rgba(255, 0, 255, 0.8) 100%) 1;
        }
        
        /* Intense Glow Effects */
        .mega-glow {
            box-shadow: 
                0 0 20px rgba(255, 0, 255, 0.8),
                0 0 40px rgba(255, 0, 255, 0.6),
                0 0 60px rgba(255, 0, 255, 0.4),
                0 0 80px rgba(0, 255, 255, 0.3),
                0 0 100px rgba(147, 51, 234, 0.2);
            animation: megaPulse 3s ease-in-out infinite alternate;
        }
        
        @keyframes megaPulse {
            0% { 
                box-shadow: 
                    0 0 20px rgba(255, 0, 255, 0.8),
                    0 0 40px rgba(255, 0, 255, 0.6),
                    0 0 60px rgba(255, 0, 255, 0.4),
                    0 0 80px rgba(0, 255, 255, 0.3);
            }
            100% { 
                box-shadow: 
                    0 0 30px rgba(255, 0, 255, 1),
                    0 0 60px rgba(255, 0, 255, 0.8),
                    0 0 90px rgba(255, 0, 255, 0.6),
                    0 0 120px rgba(0, 255, 255, 0.4),
                    0 0 150px rgba(147, 51, 234, 0.3);
            }
        }
        
        /* Enhanced Gaming Gradients */
        .gaming-gradient {
            background: linear-gradient(135deg, #ff00ff 0%, #8b5cf6 25%, #00ffff 50%, #9333ea 75%, #ff00ff 100%);
            animation: gradientShift 4s ease-in-out infinite;
        }
        
        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        
        .gaming-gradient-text {
            background: linear-gradient(135deg, #ff00ff 0%, #8b5cf6 25%, #00ffff 50%, #9333ea 75%, #ff00ff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            filter: drop-shadow(0 0 5px rgba(255, 0, 255, 0.3)) drop-shadow(0 0 8px rgba(0, 255, 255, 0.2));
        }
        
        /* Button Hover Effects with Intense Glow */
        .neon-button {
            position: relative;
            background: linear-gradient(135deg, #ff00ff, #00ffff);
            border: none;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .neon-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.5s;
        }
        
        .neon-button:hover::before {
            left: 100%;
        }
        
        .neon-button:hover {
            box-shadow: 
                0 0 20px rgba(255, 0, 255, 0.8),
                0 0 40px rgba(0, 255, 255, 0.6),
                0 0 60px rgba(255, 0, 255, 0.4);
            transform: scale(1.05);
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
                transform: translateY(-10px) rotate(2deg);
                filter: drop-shadow(0 15px 30px rgba(0, 255, 255, 0.4));
            }
            50% { 
                transform: translateY(-20px) rotate(0deg);
                filter: drop-shadow(0 20px 40px rgba(255, 0, 255, 0.5));
            }
            75% { 
                transform: translateY(-10px) rotate(-2deg);
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
            width: 200px;
            height: 200px;
            border: 2px solid rgba(255, 0, 255, 0.3);
            border-radius: 50%;
            animation: orbit 10s linear infinite;
        }
        
        .orbital-rings::before {
            content: '';
            position: absolute;
            top: -20px;
            left: -20px;
            right: -20px;
            bottom: -20px;
            border: 1px solid rgba(0, 255, 255, 0.2);
            border-radius: 50%;
            animation: orbit 15s linear infinite reverse;
        }
        
        @keyframes orbit {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Navigation Link Enhanced Glow */
        .nav-glow:hover {
            color: #ff00ff;
            text-shadow: 
                0 0 5px #ff00ff,
                0 0 10px #ff00ff,
                0 0 15px #ff00ff,
                0 0 20px #ff00ff;
            transition: all 0.3s ease;
        }
        
        /* Category Card Enhanced Effects */
        .category-glow {
            position: relative;
            overflow: hidden;
        }
        
        .category-glow::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255, 0, 255, 0.1), rgba(0, 255, 255, 0.1));
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .category-glow:hover::before {
            opacity: 1;
        }
        
        .category-glow:hover {
            border-color: rgba(255, 0, 255, 0.5);
            box-shadow: 
                0 0 20px rgba(255, 0, 255, 0.3),
                0 0 40px rgba(0, 255, 255, 0.2);
        }
    </style>
</head>
<body class="gaming-bg text-white min-h-screen">
    
    <!-- Header with Enhanced Glow -->
    <header class="relative z-20 px-6 py-8">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <!-- Logo with Intense Glow -->
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 gaming-gradient rounded-2xl flex items-center justify-center floating-glow mega-glow">
                    <span class="text-3xl">💿</span>
                </div>
                <div>
                    <h1 class="text-3xl font-bold gaming-gradient-text neon-text">
                        GameHub
                    </h1>
                    <p class="text-cyan-300 text-sm neon-text-cyan">Gaming CD Marketplace</p>
                </div>
            </div>

            <!-- Auth Buttons with Glow -->
            @if (Route::has('login'))
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" 
                           class="px-6 py-3 text-purple-300 hover:text-purple-100 nav-glow transition-all duration-300 font-medium">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                           class="px-6 py-3 text-purple-300 hover:text-white nav-glow transition-all duration-300 font-medium">
                            Log in
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" 
                               class="px-8 py-3 gaming-gradient rounded-xl font-semibold transition-all duration-300 pulse-rainbow neon-button relative overflow-hidden">
                                Register
                            </a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>
    </header>

    <!-- Hero Section with Dramatic Effects -->
    <section class="relative z-10 px-6 py-24 text-center">
        <div class="max-w-5xl mx-auto">
            <!-- Main Logo with Intense Glow -->
            <div class="relative mb-12">
                <div class="w-32 h-32 gaming-gradient rounded-full mx-auto flex items-center justify-center floating-glow pulse-rainbow mega-glow">
                    <span class="text-6xl">💿</span>
                </div>
                <!-- Orbital Rings -->
                <div class="absolute inset-0 rounded-full border-2 border-purple-500/20 animate-spin" style="animation-duration: 20s;"></div>
                <div class="absolute inset-4 rounded-full border border-cyan-500/20 animate-spin" style="animation-duration: 15s; animation-direction: reverse;"></div>
            </div>
            
            <!-- Enhanced Title -->
            <h2 class="text-7xl md:text-8xl font-black mb-8 leading-tight">
                <span class="gaming-gradient-text neon-text-rainbow block mb-2">
                    Gaming CD
                </span>
                <span class="neon-text-cyan">
                    HUB
                </span>
            </h2>
            
            <!-- Subtitle with Glow -->
            <p class="text-2xl md:text-3xl text-purple-200 mb-12 neon-text">
                Buy, sell & rent gaming CDs with fellow gamers
            </p>
            <p class="text-lg text-cyan-300 mb-16 neon-text-cyan opacity-80">
                The ultimate marketplace for gaming enthusiasts
            </p>

            <!-- Enhanced Action Buttons -->
            <div class="flex flex-col lg:flex-row gap-6 justify-center items-center mb-16">
                <a href="{{ route('login') }}" class="group px-12 py-5 gaming-gradient rounded-2xl font-bold text-xl transition-all duration-400 flex items-center gap-3 card-hover relative overflow-hidden neon-button">
                    <span class="text-3xl group-hover:scale-125 transition-transform duration-300">💿</span> 
                    <span>Browse Collection</span>
                </a>
                <a href="{{ route('login') }}" class="group px-12 py-5 glass-card rounded-2xl font-bold text-xl transition-all duration-400 flex items-center gap-3 card-hover neon-button">
                    <span class="text-3xl group-hover:scale-125 transition-transform duration-300">📈</span>
                    <span>Start Selling</span>
                </a>
                <a href="{{ route('login') }}" class="group px-12 py-5 glass-card rounded-2xl font-bold text-xl transition-all duration-400 flex items-center gap-3 card-hover neon-button">
                    <span class="text-3xl group-hover:scale-125 transition-transform duration-300">📅</span>
                    <span>Rent Games</span>
                </a>
            </div>

            <!-- Stats with Glow Effects -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="glass-card rounded-xl p-6 text-center card-hover">
                    <div class="text-4xl font-bold neon-text mb-2">10K+</div>
                    <div class="text-cyan-300 neon-text-cyan">Active Gamers</div>
                </div>
                <div class="glass-card rounded-xl p-6 text-center card-hover">
                    <div class="text-4xl font-bold neon-text mb-2">50K+</div>
                    <div class="text-cyan-300 neon-text-cyan">Games Available</div>
                </div>
                <div class="glass-card rounded-xl p-6 text-center card-hover">
                    <div class="text-4xl font-bold neon-text mb-2">99.9%</div>
                    <div class="text-cyan-300 neon-text-cyan">Satisfaction Rate</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features with Enhanced Design -->
    <section class="relative z-10 px-6 py-24">
        <div class="max-w-7xl mx-auto">
            <!-- Section Header -->
            <div class="text-center mb-20">
                <h3 class="text-5xl font-black mb-6">
                    <span class="gaming-gradient-text neon-text-rainbow">
                        Why Choose GameHub?
                    </span>
                </h3>
                <p class="text-2xl text-cyan-300 neon-text-cyan">The ultimate gaming marketplace experience</p>
                <div class="w-32 h-1 gaming-gradient mx-auto mt-6 rounded-full"></div>
            </div>

            <!-- Feature Cards Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                <!-- Buy CDs Card -->
                <div class="glass-card rounded-3xl p-10 text-center card-hover relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1 gaming-gradient"></div>
                    <div class="w-24 h-24 gaming-gradient rounded-2xl flex items-center justify-center mb-8 mx-auto floating-glow">
                        <span class="text-4xl">💰</span>
                    </div>
                    <h4 class="text-2xl font-bold text-white mb-6 neon-text">Buy Premium CDs</h4>
                    <p class="text-lg text-purple-300 mb-8 leading-relaxed">
                        Discover rare and popular gaming CDs at unbeatable prices from our verified seller community.
                    </p>
                    <a href="{{ route('login') }}" class="inline-flex items-center text-cyan-400 font-semibold hover:text-cyan-300 transition-colors cursor-pointer group">
                        <span class="mr-2">Explore Collection</span>
                        <span class="group-hover:translate-x-2 transition-transform">→</span>
                    </a>
                </div>

                <!-- Sell CDs Card -->
                <div class="glass-card rounded-3xl p-10 text-center card-hover relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-purple-600 to-pink-600"></div>
                    <div class="w-24 h-24 bg-gradient-to-r from-purple-600 to-pink-600 rounded-2xl flex items-center justify-center mb-8 mx-auto pulse-rainbow">
                        <span class="text-4xl">📈</span>
                    </div>
                    <h4 class="text-2xl font-bold text-white mb-6 neon-text">Sell Your Collection</h4>
                    <p class="text-lg text-purple-300 mb-8 leading-relaxed">
                        Transform your gaming library into profit with our secure listing platform and instant payments.
                    </p>
                    <a href="{{ route('login') }}" class="inline-flex items-center text-purple-400 font-semibold hover:text-purple-300 transition-colors cursor-pointer group">
                        <span class="mr-2">Start Selling</span>
                        <span class="group-hover:translate-x-2 transition-transform">→</span>
                    </a>
                </div>

                <!-- Rent CDs Card -->
                <div class="glass-card rounded-3xl p-10 text-center card-hover relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-cyan-500 to-blue-500"></div>
                    <div class="w-24 h-24 bg-gradient-to-r from-cyan-500 to-blue-500 rounded-2xl flex items-center justify-center mb-8 mx-auto floating-glow">
                        <span class="text-4xl">📅</span>
                    </div>
                    <h4 class="text-2xl font-bold text-white mb-6 neon-text">Flexible Rentals</h4>
                    <p class="text-lg text-purple-300 mb-8 leading-relaxed">
                        Try before you buy with our affordable rental system. Perfect for testing new games.
                    </p>
                    <a href="{{ route('login') }}" class="inline-flex items-center text-cyan-400 font-semibold hover:text-cyan-300 transition-colors cursor-pointer group">
                        <span class="mr-2">Browse Rentals</span>
                        <span class="group-hover:translate-x-2 transition-transform">→</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Categories with Gaming Vibe -->
    <section class="relative z-10 px-6 py-24">
        <div class="max-w-7xl mx-auto">
            <!-- Section Header -->
            <div class="text-center mb-20">
                <h3 class="text-5xl font-black mb-6 gaming-gradient-text neon-text">Gaming Categories</h3>
                <p class="text-2xl text-cyan-300 neon-text-cyan">Find your perfect game genre</p>
                <div class="w-32 h-1 gaming-gradient mx-auto mt-6 rounded-full"></div>
            </div>

            <!-- Enhanced Category Grid -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="glass-card rounded-2xl p-8 text-center transition-all duration-300 category-glow card-hover">
                    <div class="text-5xl mb-6">🎯</div>
                    <div class="text-xl font-bold text-white mb-3 neon-text">Action</div>
                    <div class="text-cyan-400 text-sm neon-text-cyan">250+ Epic Games</div>
                    <div class="w-full h-1 gaming-gradient mt-4 rounded-full opacity-50"></div>
                </div>
                
                <div class="glass-card rounded-2xl p-8 text-center transition-all duration-300 category-glow card-hover">
                    <div class="text-5xl mb-6">🏎️</div>
                    <div class="text-xl font-bold text-white mb-3 neon-text">Racing</div>
                    <div class="text-cyan-400 text-sm neon-text-cyan">180+ Speed Games</div>
                    <div class="w-full h-1 bg-gradient-to-r from-purple-600 to-pink-600 mt-4 rounded-full opacity-50"></div>
                </div>
                
                <div class="glass-card rounded-2xl p-8 text-center transition-all duration-300 category-glow card-hover">
                    <div class="text-5xl mb-6">⚔️</div>
                    <div class="text-xl font-bold text-white mb-3 neon-text">RPG</div>
                    <div class="text-cyan-400 text-sm neon-text-cyan">320+ Adventures</div>
                    <div class="w-full h-1 bg-gradient-to-r from-cyan-500 to-blue-500 mt-4 rounded-full opacity-50"></div>
                </div>
                
                <div class="glass-card rounded-2xl p-8 text-center transition-all duration-300 category-glow card-hover">
                    <div class="text-5xl mb-6">⚽</div>
                    <div class="text-xl font-bold text-white mb-3 neon-text">Sports</div>
                    <div class="text-cyan-400 text-sm neon-text-cyan">140+ Sport Games</div>
                    <div class="w-full h-1 gaming-gradient mt-4 rounded-full opacity-50"></div>
                </div>
            </div>

            <!-- Additional Categories Row -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 mt-8">
                <div class="glass-card rounded-2xl p-8 text-center transition-all duration-300 category-glow card-hover">
                    <div class="text-5xl mb-6">🧩</div>
                    <div class="text-xl font-bold text-white mb-3 neon-text">Puzzle</div>
                    <div class="text-cyan-400 text-sm neon-text-cyan">95+ Brain Games</div>
                    <div class="w-full h-1 bg-gradient-to-r from-purple-600 to-pink-600 mt-4 rounded-full opacity-50"></div>
                </div>
                
                <div class="glass-card rounded-2xl p-8 text-center transition-all duration-300 category-glow card-hover">
                    <div class="text-5xl mb-6">👻</div>
                    <div class="text-xl font-bold text-white mb-3 neon-text">Horror</div>
                    <div class="text-cyan-400 text-sm neon-text-cyan">75+ Scary Games</div>
                    <div class="w-full h-1 bg-gradient-to-r from-red-600 to-orange-600 mt-4 rounded-full opacity-50"></div>
                </div>
                
                <div class="glass-card rounded-2xl p-8 text-center transition-all duration-300 category-glow card-hover">
                    <div class="text-5xl mb-6">🚀</div>
                    <div class="text-xl font-bold text-white mb-3 neon-text">Sci-Fi</div>
                    <div class="text-cyan-400 text-sm neon-text-cyan">120+ Future Games</div>
                    <div class="w-full h-1 bg-gradient-to-r from-cyan-500 to-blue-500 mt-4 rounded-full opacity-50"></div>
                </div>
                
                <div class="glass-card rounded-2xl p-8 text-center transition-all duration-300 category-glow card-hover">
                    <div class="text-5xl mb-6">🎮</div>
                    <div class="text-xl font-bold text-white mb-3 neon-text">Retro</div>
                    <div class="text-cyan-400 text-sm neon-text-cyan">200+ Classic Games</div>
                    <div class="w-full h-1 gaming-gradient mt-4 rounded-full opacity-50"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Enhanced Footer with Glow -->
    <footer class="relative z-10 px-6 py-16 border-t border-purple-800/30 mt-20">
        <div class="max-w-7xl mx-auto">
            <!-- Footer Content -->
            <div class="text-center mb-12">
                <div class="flex items-center justify-center gap-4 mb-8">
                    <div class="w-16 h-16 gaming-gradient rounded-2xl flex items-center justify-center floating-glow">
                        <span class="text-2xl">💿</span>
                    </div>
                    <div>
                        <span class="text-3xl font-black gaming-gradient-text neon-text block">
                            GameHub
                        </span>
                        <span class="text-sm text-cyan-300 neon-text-cyan">Gaming CD Marketplace</span>
                    </div>
                </div>
                
                <p class="text-xl text-purple-300 mb-8 neon-text max-w-2xl mx-auto">
                    The trusted marketplace where gaming enthusiasts connect, trade, and discover their next favorite game.
                </p>

                <!-- Social Links -->
                <div class="flex justify-center gap-6 mb-8">
                    <div class="w-12 h-12 glass-card rounded-xl flex items-center justify-center card-hover cursor-pointer">
                        <span class="text-xl">📧</span>
                    </div>
                    <div class="w-12 h-12 glass-card rounded-xl flex items-center justify-center card-hover cursor-pointer">
                        <span class="text-xl">📱</span>
                    </div>
                    <div class="w-12 h-12 glass-card rounded-xl flex items-center justify-center card-hover cursor-pointer">
                        <span class="text-xl">💬</span>
                    </div>
                    <div class="w-12 h-12 glass-card rounded-xl flex items-center justify-center card-hover cursor-pointer">
                        <span class="text-xl">🌐</span>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-12">
                    <div class="glass-card rounded-xl p-4 card-hover">
                        <h4 class="font-bold text-white mb-2 neon-text">Marketplace</h4>
                        <div class="text-purple-300 text-sm space-y-1">
                            <div class="hover:text-cyan-300 cursor-pointer">Buy Games</div>
                            <div class="hover:text-cyan-300 cursor-pointer">Sell Games</div>
                            <div class="hover:text-cyan-300 cursor-pointer">Rent Games</div>
                        </div>
                    </div>
                    <div class="glass-card rounded-xl p-4 card-hover">
                        <h4 class="font-bold text-white mb-2 neon-text">Support</h4>
                        <div class="text-purple-300 text-sm space-y-1">
                            <div class="hover:text-cyan-300 cursor-pointer">Help Center</div>
                            <div class="hover:text-cyan-300 cursor-pointer">Contact Us</div>
                            <div class="hover:text-cyan-300 cursor-pointer">FAQ</div>
                        </div>
                    </div>
                    <div class="glass-card rounded-xl p-4 card-hover">
                        <h4 class="font-bold text-white mb-2 neon-text">Community</h4>
                        <div class="text-purple-300 text-sm space-y-1">
                            <div class="hover:text-cyan-300 cursor-pointer">Forums</div>
                            <div class="hover:text-cyan-300 cursor-pointer">Discord</div>
                            <div class="hover:text-cyan-300 cursor-pointer">Events</div>
                        </div>
                    </div>
                    <div class="glass-card rounded-xl p-4 card-hover">
                        <h4 class="font-bold text-white mb-2 neon-text">Legal</h4>
                        <div class="text-purple-300 text-sm space-y-1">
                            <div class="hover:text-cyan-300 cursor-pointer">Privacy</div>
                            <div class="hover:text-cyan-300 cursor-pointer">Terms</div>
                            <div class="hover:text-cyan-300 cursor-pointer">Cookies</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="pt-8 border-t border-purple-800/20 text-center">
                <p class="text-purple-400 text-sm mb-2">
                    © 2024 GameHub. All rights reserved.
                </p>
                <p class="text-purple-500 text-xs">
                    Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
                </p>
            </div>
        </div>
    </footer>
</body>
</html>