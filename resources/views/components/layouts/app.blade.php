<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'GameHub') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles

    <style>
        /* Gaming Theme Custom Styles */
        body {
            background: linear-gradient(135deg, #0f0f23 0%, #1a1a2e 50%, #16213e 100%);
            background-attachment: fixed;
            min-height: 100vh;
        }

        /* Animated Background Particles */
        .particles-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
            pointer-events: none;
        }

        .particle {
            position: absolute;
            width: 2px;
            height: 2px;
            background: #00ff88;
            border-radius: 50%;
            animation: float 8s ease-in-out infinite;
            opacity: 0.6;
        }

        @keyframes float {
            0%, 100% { 
                transform: translateY(0px) rotate(0deg); 
                opacity: 0.6;
            }
            50% { 
                transform: translateY(-30px) rotate(180deg); 
                opacity: 1;
            }
        }

        /* Gaming Navigation Overrides */
        .gaming-nav {
            background: rgba(15, 15, 35, 0.95) !important;
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0, 255, 136, 0.2) !important;
        }

        .gaming-nav .bg-white {
            background: rgba(15, 15, 35, 0.95) !important;
        }

        .gaming-nav .text-gray-500 {
            color: #ccc !important;
        }

        .gaming-nav .text-gray-500:hover {
            color: #00ff88 !important;
        }

        .gaming-nav .text-gray-900 {
            color: #fff !important;
        }

        .gaming-nav .border-gray-300 {
            border-color: rgba(0, 255, 136, 0.2) !important;
        }

        /* Gaming Header Overrides */
        .gaming-header {
            background: rgba(26, 26, 46, 0.9) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 255, 136, 0.2) !important;
        }

        .gaming-header .bg-white {
            background: rgba(26, 26, 46, 0.9) !important;
        }

        .gaming-header .text-gray-800 {
            color: #fff !important;
        }

        /* Gaming Content Area */
        .gaming-content {
            background: transparent !important;
        }

        .gaming-content .bg-gray-100 {
            background: transparent !important;
        }

        /* Gaming Cards and Forms */
        .gaming-card {
            background: rgba(26, 26, 46, 0.8);
            border: 1px solid rgba(0, 255, 136, 0.2);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .gaming-card:hover {
            border-color: rgba(0, 255, 136, 0.4);
            box-shadow: 0 8px 25px rgba(0, 255, 136, 0.15);
        }

        /* Button Styles */
        .btn-gaming-primary {
            background: linear-gradient(45deg, #00ff88, #00cc6a) !important;
            color: #000 !important;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-gaming-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 255, 136, 0.3);
        }

        .btn-gaming-secondary {
            background: rgba(26, 26, 46, 0.8) !important;
            color: #00ff88 !important;
            border: 1px solid rgba(0, 255, 136, 0.4);
            transition: all 0.3s ease;
        }

        .btn-gaming-secondary:hover {
            background: rgba(0, 255, 136, 0.1) !important;
            transform: translateY(-1px);
        }

        /* Input Styles */
        .input-gaming {
            background: rgba(15, 15, 35, 0.8) !important;
            border: 1px solid rgba(0, 255, 136, 0.2) !important;
            color: #fff !important;
            transition: all 0.3s ease;
        }

        .input-gaming:focus {
            border-color: rgba(0, 255, 136, 0.6) !important;
            box-shadow: 0 0 0 3px rgba(0, 255, 136, 0.1) !important;
            outline: none !important;
        }

        .input-gaming::placeholder {
            color: #888 !important;
        }

        /* Text Colors */
        .text-gaming-primary {
            color: #00ff88 !important;
        }

        .text-gaming-secondary {
            color: #ccc !important;
        }

        .text-gaming-white {
            color: #fff !important;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(15, 15, 35, 0.8);
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(45deg, #00ff88, #00cc6a);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(45deg, #00cc6a, #00ff88);
        }

        /* Logo Animation */
        .gaming-logo {
            transition: all 0.3s ease;
        }

        .gaming-logo:hover {
            transform: scale(1.05);
            filter: drop-shadow(0 0 10px rgba(0, 255, 136, 0.5));
        }

        /* Glow effects */
        .glow-text {
            text-shadow: 0 0 10px rgba(0, 255, 136, 0.5);
        }

        .glow-border {
            box-shadow: 0 0 20px rgba(0, 255, 136, 0.2);
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .particles-bg {
                display: none;
            }
        }
    </style>
</head>
<body class="font-sans antialiased text-white">
    <!-- Animated Background Particles -->
    <div class="particles-bg" id="particles"></div>

    <x-banner />

    <div class="min-h-screen gaming-content">
        <!-- Navigation with Gaming Theme -->
        <div class="gaming-nav">
            @livewire('navigation-menu')
        </div>

        <!-- Page Heading with Gaming Theme -->
        @if (isset($header))
            <header class="gaming-header shadow-lg">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-r from-green-400 to-green-600 rounded-lg flex items-center justify-center">
                            <span class="text-xl">🎮</span>
                        </div>
                        <div class="text-gaming-white font-bold text-xl glow-text">
                            {{ $header }}
                        </div>
                    </div>
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main class="relative z-10">
            {{ $slot }}
        </main>
    </div>

    @stack('modals')

    @livewireScripts

    <!-- Particles Animation Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const particlesContainer = document.getElementById('particles');
            const particleCount = window.innerWidth > 768 ? 50 : 20;

            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                
                // Random position
                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = Math.random() * 100 + '%';
                
                // Random animation delay and duration
                particle.style.animationDelay = Math.random() * 8 + 's';
                particle.style.animationDuration = (Math.random() * 4 + 6) + 's';
                
                particlesContainer.appendChild(particle);
            }
        });

        // Add gaming classes to existing elements
        document.addEventListener('DOMContentLoaded', function() {
            // Style navigation elements
            const navElements = document.querySelectorAll('nav');
            navElements.forEach(nav => {
                nav.classList.add('gaming-nav');
            });

            // Style buttons
            const buttons = document.querySelectorAll('button');
            buttons.forEach(button => {
                if (button.classList.contains('bg-blue-500') || 
                    button.classList.contains('bg-indigo-600') ||
                    button.textContent.includes('Post') ||
                    button.textContent.includes('Save') ||
                    button.textContent.includes('Submit')) {
                    button.classList.add('btn-gaming-primary');
                } else if (!button.classList.contains('btn-gaming-primary')) {
                    button.classList.add('btn-gaming-secondary');
                }
            });

            // Style inputs
            const inputs = document.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                input.classList.add('input-gaming');
            });

            // Style cards and containers
            const cards = document.querySelectorAll('.bg-white, .bg-gray-50, .bg-gray-100');
            cards.forEach(card => {
                if (!card.closest('nav') && !card.closest('header')) {
                    card.classList.add('gaming-card');
                }
            });
        });
    </script>
</body>
</html>