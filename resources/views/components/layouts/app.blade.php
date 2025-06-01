<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'GameHub') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles

    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            overflow-x: hidden;
        }
        
        /* Enhanced Gaming Background with Blue/Cyan Theme */
        body {
            background: 
                radial-gradient(circle at 25% 25%, #0a1a2e 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, #0e213e 0%, transparent 50%),
                radial-gradient(circle at 50% 0%, #0f1419 0%, transparent 50%),
                linear-gradient(180deg, #000814 0%, #001d3d 50%, #000814 100%);
            background-attachment: fixed;
            min-height: 100vh;
            position: relative;
        }

        /* Animated Background Glow Effects */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 30%, rgba(0, 150, 255, 0.25) 0%, transparent 40%),
                radial-gradient(circle at 80% 20%, rgba(0, 255, 255, 0.2) 0%, transparent 40%),
                radial-gradient(circle at 40% 70%, rgba(51, 144, 234, 0.3) 0%, transparent 40%),
                radial-gradient(circle at 60% 40%, rgba(85, 170, 247, 0.15) 0%, transparent 50%);
            pointer-events: none;
            z-index: 1;
            animation: backgroundPulse 8s ease-in-out infinite alternate;
        }
        
        @keyframes backgroundPulse {
            0% { opacity: 0.7; }
            100% { opacity: 1; }
        }

        /* Enhanced Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: rgba(0, 20, 40, 0.3);
        }
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #0096ff, #00ffff);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 150, 255, 0.5);
        }
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #00ffff, #0096ff);
        }
        
        /* Scroll Progress Indicator */
        .scroll-progress {
            position: fixed;
            top: 0;
            left: 0;
            width: 0;
            height: 3px;
            background: linear-gradient(90deg, #0096ff, #00ffff, #0096ff);
            z-index: 1001;
            transition: width 0.1s;
            box-shadow: 0 0 10px rgba(0, 150, 255, 0.8);
        }

        /* Animated Background Particles */
        .particles-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 2;
            overflow: hidden;
            pointer-events: none;
        }

        .particle {
            position: absolute;
            width: 2px;
            height: 2px;
            background: #00a6ff;
            border-radius: 50%;
            animation: float 8s ease-in-out infinite;
            opacity: 0.6;
            box-shadow: 0 0 6px rgba(0, 166, 255, 0.8);
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
            background: rgba(5, 20, 35, 0.95) !important;
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0, 150, 255, 0.3) !important;
            box-shadow: 0 4px 20px rgba(0, 150, 255, 0.1);
            position: relative;
            z-index: 10;
        }

        .gaming-nav .bg-white {
            background: rgba(5, 20, 35, 0.95) !important;
        }

        .gaming-nav .text-gray-500 {
            color: #a8dadc !important;
            transition: all 0.3s ease;
        }

        .gaming-nav .text-gray-500:hover {
            color: #00ffff !important;
            text-shadow: 0 0 10px rgba(0, 255, 255, 0.5);
        }

        .gaming-nav .text-gray-900 {
            color: #fff !important;
        }

        .gaming-nav .border-gray-300 {
            border-color: rgba(0, 150, 255, 0.3) !important;
        }

        /* Gaming Header Overrides */
        .gaming-header {
            background: rgba(10, 25, 40, 0.9) !important;
            backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(0, 150, 255, 0.3) !important;
            box-shadow: 0 2px 15px rgba(0, 150, 255, 0.1);
            position: relative;
            z-index: 10;
        }

        .gaming-header .bg-white {
            background: rgba(10, 25, 40, 0.9) !important;
        }

        .gaming-header .text-gray-800 {
            color: #fff !important;
        }

        /* Enhanced Gaming Content Area */
        .gaming-content {
            background: transparent !important;
            position: relative;
            z-index: 5;
        }

        .gaming-content .bg-gray-100 {
            background: transparent !important;
        }

        /* Enhanced Gaming Cards with Glass Effect */
        .gaming-card {
            background: rgba(5, 15, 25, 0.8);
            backdrop-filter: blur(15px);
            border: 2px solid;
            border-image: linear-gradient(135deg, 
                rgba(0, 150, 255, 0.4) 0%, 
                rgba(0, 255, 255, 0.2) 50%, 
                rgba(51, 144, 234, 0.4) 100%) 1;
            box-shadow: 
                0 8px 32px rgba(0, 150, 255, 0.15),
                inset 0 1px 0 rgba(255, 255, 255, 0.05);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border-radius: 16px;
        }

        .gaming-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 
                0 20px 40px rgba(0, 150, 255, 0.3),
                0 0 30px rgba(0, 255, 255, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            border-image: linear-gradient(135deg, 
                rgba(0, 150, 255, 0.6) 0%, 
                rgba(0, 255, 255, 0.4) 50%, 
                rgba(0, 150, 255, 0.6) 100%) 1;
        }

        /* Enhanced Button Styles */
        .btn-gaming-primary {
            background: linear-gradient(135deg, #0096ff 0%, #00ffff 100%) !important;
            color: white !important;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            border: none;
            border-radius: 12px;
            padding: 12px 24px;
            box-shadow: 
                0 4px 15px rgba(0, 150, 255, 0.3),
                0 0 20px rgba(0, 255, 255, 0.2);
        }

        .btn-gaming-primary:hover {
            transform: translateY(-2px);
            box-shadow: 
                0 8px 25px rgba(0, 150, 255, 0.4),
                0 0 30px rgba(0, 255, 255, 0.3);
        }

        .btn-gaming-secondary {
            background: rgba(10, 25, 40, 0.8) !important;
            color: #00a6ff !important;
            border: 2px solid rgba(0, 150, 255, 0.4);
            transition: all 0.3s ease;
            border-radius: 12px;
            padding: 10px 20px;
        }

        .btn-gaming-secondary:hover {
            background: rgba(0, 150, 255, 0.1) !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(0, 150, 255, 0.2);
        }

        /* Enhanced Input Styles */
        .input-gaming {
            background: rgba(5, 20, 35, 0.8) !important;
            border: 2px solid rgba(0, 150, 255, 0.3) !important;
            color: #fff !important;
            transition: all 0.3s ease;
            border-radius: 12px;
            backdrop-filter: blur(10px);
        }

        .input-gaming:focus {
            border-color: rgba(0, 150, 255, 0.6) !important;
            box-shadow: 
                0 0 0 3px rgba(0, 150, 255, 0.1) !important,
                0 0 20px rgba(0, 255, 255, 0.2) !important;
            outline: none !important;
        }

        .input-gaming::placeholder {
            color: #888 !important;
        }

        /* Enhanced Text Colors with Glow */
        .text-gaming-primary {
            color: #00a6ff !important;
            text-shadow: 0 0 10px rgba(0, 166, 255, 0.5);
        }

        .text-gaming-secondary {
            color: #a8dadc !important;
        }

        .text-gaming-white {
            color: #fff !important;
        }

        /* Neon Text Effects */
        .neon-text-blue {
            color: #ffffff;
            text-shadow: 
                0 0 5px rgba(0, 150, 255, 0.4),
                0 0 10px rgba(0, 150, 255, 0.2),
                0 0 15px rgba(0, 150, 255, 0.1);
        }

        .neon-text-cyan {
            color: #ffffff;
            text-shadow: 
                0 0 5px rgba(0, 255, 255, 0.4),
                0 0 10px rgba(0, 255, 255, 0.2),
                0 0 15px rgba(0, 255, 255, 0.1);
        }

        .neon-text-gradient {
            background: linear-gradient(45deg, #0096ff, #00ffff, #0096ff, #00ffff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: rainbowShift 3s ease-in-out infinite;
            filter: drop-shadow(0 0 3px rgba(0, 150, 255, 0.3)) drop-shadow(0 0 6px rgba(0, 255, 255, 0.2));
        }
        
        @keyframes rainbowShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Enhanced Logo Animation */
        .gaming-logo {
            transition: all 0.3s ease;
        }

        .gaming-logo:hover {
            transform: scale(1.1);
            filter: drop-shadow(0 0 15px rgba(0, 150, 255, 0.6));
        }

        /* Enhanced Glow effects */
        .glow-text {
            text-shadow: 
                0 0 10px rgba(0, 150, 255, 0.5),
                0 0 20px rgba(0, 255, 255, 0.3);
        }

        .glow-border {
            box-shadow: 
                0 0 20px rgba(0, 150, 255, 0.3),
                0 0 40px rgba(0, 255, 255, 0.2);
        }

        /* Floating Animation */
        .float-animation {
            animation: floatGently 6s ease-in-out infinite;
        }
        
        @keyframes floatGently {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        /* Pulse Animation */
        .pulse-glow {
            animation: pulseGlow 2s ease-in-out infinite;
        }
        
        @keyframes pulseGlow {
            0%, 100% { 
                box-shadow: 0 0 20px rgba(0, 150, 255, 0.3);
            }
            50% { 
                box-shadow: 0 0 30px rgba(0, 150, 255, 0.5);
            }
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .particles-bg {
                display: none;
            }
            
            body::before {
                opacity: 0.5;
            }
        }

        /* Gaming Dropdown Styling */
        .gaming-dropdown {
            background: rgba(5, 15, 25, 0.95) !important;
            backdrop-filter: blur(20px);
            border: 1px solid rgba(0, 150, 255, 0.3) !important;
            border-radius: 12px !important;
            box-shadow: 
                0 10px 30px rgba(0, 0, 0, 0.5),
                0 0 20px rgba(0, 150, 255, 0.2) !important;
            overflow: hidden;
        }

        .gaming-dropdown a {
            color: #e1e8ed !important;
            transition: all 0.3s ease;
            border-radius: 8px;
            margin: 2px;
        }

        .gaming-dropdown a:hover {
            background: rgba(0, 150, 255, 0.1) !important;
            color: #00a6ff !important;
            text-shadow: 0 0 8px rgba(0, 150, 255, 0.4);
        }

        .gaming-dropdown .block {
            color: #a0a9b8 !important;
            background: rgba(0, 150, 255, 0.05) !important;
            border-bottom: 1px solid rgba(0, 150, 255, 0.1) !important;
        }

        /* Force high z-index for all dropdown elements */
        .relative .absolute {
            z-index: 9999 !important;
        }

        /* Navigation z-index boost */
        nav {
            z-index: 1000 !important;
        }

        /* Enhanced Form Component Styles */
        .gaming-label {
            text-shadow: 0 0 8px rgba(0, 255, 255, 0.3);
            font-weight: 600;
        }

        .gaming-input {
            border: 1px solid rgba(0, 150, 255, 0.3);
            transition: all 0.3s ease;
        }

        .gaming-input:focus {
            box-shadow: 
                0 0 20px rgba(0, 255, 255, 0.3),
                inset 0 0 10px rgba(0, 150, 255, 0.1);
            border-color: #00ffff;
        }

        .gaming-button {
            position: relative;
            overflow: hidden;
            text-shadow: 0 0 8px rgba(255, 255, 255, 0.3);
        }

        .gaming-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .gaming-button:hover::before {
            left: 100%;
        }

        .gaming-secondary-button {
            position: relative;
            backdrop-filter: blur(10px);
        }

        .gaming-checkbox {
            appearance: none;
            -webkit-appearance: none;
            position: relative;
        }

        .gaming-checkbox::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 12px;
            height: 12px;
            border-radius: 2px;
            background: transparent;
            border: 2px solid #00ffff;
            transition: all 0.3s ease;
        }

        .gaming-checkbox:checked::before {
            background: #00ffff;
            box-shadow: 0 0 10px rgba(0, 255, 255, 0.5);
        }

        .gaming-checkbox:checked::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #000;
            font-size: 10px;
            font-weight: bold;
        }

        .gaming-error {
            text-shadow: 0 0 5px rgba(255, 100, 100, 0.5);
        }

        .gaming-validation-errors {
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 15px rgba(255, 0, 0, 0.1);
        }

        .gaming-action-message {
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 15px rgba(0, 255, 0, 0.1);
            text-shadow: 0 0 5px rgba(0, 255, 0, 0.3);
        }

        /* Enhanced Pagination Styling */
        .pagination {
            display: flex;
            gap: 0.5rem;
        }

        .pagination > nav {
            background: transparent !important;
        }

        .pagination svg {
            width: 1.25rem;
            height: 1.25rem;
            fill: currentColor;
        }

        .pagination span.cursor-default {
            color: rgba(100, 116, 139, 0.5);
        }

        .pagination span:not(.cursor-default),
        .pagination button,
        .pagination a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 2.5rem;
            height: 2.5rem;
            padding: 0 0.75rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            color: #e2e8f0;
            border: 1px solid rgba(0, 150, 255, 0.2);
            background: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(10px);
        }

        .pagination span[aria-current="page"] {
            background: linear-gradient(135deg, rgba(0, 122, 255, 0.7), rgba(0, 255, 255, 0.5)) !important;
            color: white !important;
            font-weight: 600;
            box-shadow: 0 0 15px rgba(0, 150, 255, 0.4);
            border-color: rgba(0, 150, 255, 0.5);
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
        }

        .pagination a:hover,
        .pagination button:not([disabled]):hover {
            background: rgba(30, 41, 59, 0.7);
            border-color: rgba(0, 150, 255, 0.4);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 150, 255, 0.2);
        }

        .pagination a:focus,
        .pagination button:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(0, 150, 255, 0.5);
        }
    </style>

</head>
<body class="font-sans antialiased text-white">
    <!-- Animated Background Particles -->
    <div class="particles-bg" id="particles"></div>
    
    <!-- Scroll Progress Indicator -->
    <div class="scroll-progress" id="scrollProgress"></div>

    <x-banner />

    <div class="min-h-screen gaming-content">
        <!-- Navigation with Gaming Theme -->
        <div class="gaming-nav">
            @livewire('navigation-menu')
        </div>

        <!-- Page Heading with Enhanced Gaming Theme -->
        @if (isset($header))
            <header class="gaming-header shadow-lg">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-400 rounded-lg flex items-center justify-center float-animation pulse-glow">
                            <span class="text-2xl">🛒</span>
                        </div>
                        <div class="neon-text-gradient font-bold text-2xl">
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

    <!-- Enhanced Particles Animation Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const particlesContainer = document.getElementById('particles');
            const particleCount = window.innerWidth > 768 ? 60 : 25;

            // Create initial particles
            for (let i = 0; i < particleCount; i++) {
                createParticle();
            }

            function createParticle() {
                const particle = document.createElement('div');
                particle.className = 'particle';
                
                // Random position
                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = Math.random() * 100 + '%';
                
                // Random animation delay and duration
                particle.style.animationDelay = Math.random() * 8 + 's';
                particle.style.animationDuration = (Math.random() * 4 + 6) + 's';
                
                particlesContainer.appendChild(particle);

                // Remove particle after animation cycle and create new one
                setTimeout(() => {
                    if (particle.parentNode) {
                        particle.remove();
                    }
                    createParticle();
                }, (Math.random() * 4 + 6) * 1000);
            }
        });

        // Enhanced gaming classes application
        document.addEventListener('DOMContentLoaded', function() {
            // Apply gaming theme after a short delay to ensure DOM is ready
            setTimeout(() => {
                // Style navigation elements
                const navElements = document.querySelectorAll('nav');
                navElements.forEach(nav => {
                    nav.classList.add('gaming-nav');
                });

                // Style buttons with enhanced detection
                const buttons = document.querySelectorAll('button');
                buttons.forEach(button => {
                    const buttonText = button.textContent.toLowerCase();
                    if (buttonText.includes('post') || 
                        buttonText.includes('save') || 
                        buttonText.includes('submit') ||
                        buttonText.includes('buy') ||
                        buttonText.includes('add') ||
                        buttonText.includes('create') ||
                        button.classList.contains('bg-blue-500') || 
                        button.classList.contains('bg-indigo-600')) {
                        button.classList.add('btn-gaming-primary');
                    } else if (!button.classList.contains('btn-gaming-primary')) {
                        button.classList.add('btn-gaming-secondary');
                    }
                });

                // Style inputs with enhanced detection
                const inputs = document.querySelectorAll('input, textarea, select');
                inputs.forEach(input => {
                    input.classList.add('input-gaming');
                });

                // Style cards and containers with enhanced detection
                const cards = document.querySelectorAll('.bg-white, .bg-gray-50, .bg-gray-100, .bg-slate-100, .bg-slate-50');
                cards.forEach(card => {
                    if (!card.closest('nav') && !card.closest('header')) {
                        card.classList.add('gaming-card');
                    }
                });

                // Add glow effects to important text
                const headings = document.querySelectorAll('h1, h2, h3');
                headings.forEach(heading => {
                    if (!heading.closest('header')) {
                        heading.classList.add('text-gaming-primary');
                    }
                });

                // Add floating animation to cards
                const cardElements = document.querySelectorAll('.gaming-card');
                cardElements.forEach((card, index) => {
                    // Stagger the animation delays
                    card.style.animationDelay = (index * 0.2) + 's';
                });

            }, 100);
        });

        // Add real-time style application for dynamic content
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === 1) { // Element node
                        // Apply gaming styles to new buttons
                        const newButtons = node.querySelectorAll ? node.querySelectorAll('button') : [];
                        newButtons.forEach(button => {
                            const buttonText = button.textContent.toLowerCase();
                            if (buttonText.includes('post') || 
                                buttonText.includes('save') || 
                                buttonText.includes('submit') ||
                                buttonText.includes('buy') ||
                                buttonText.includes('add') ||
                                buttonText.includes('create')) {
                                button.classList.add('btn-gaming-primary');
                            } else {
                                button.classList.add('btn-gaming-secondary');
                            }
                        });

                        // Apply gaming styles to new inputs
                        const newInputs = node.querySelectorAll ? node.querySelectorAll('input, textarea, select') : [];
                        newInputs.forEach(input => {
                            input.classList.add('input-gaming');
                        });

                        // Apply gaming styles to new cards
                        const newCards = node.querySelectorAll ? node.querySelectorAll('.bg-white, .bg-gray-50, .bg-gray-100, .bg-slate-100, .bg-slate-50') : [];
                        newCards.forEach(card => {
                            if (!card.closest('nav') && !card.closest('header')) {
                                card.classList.add('gaming-card');
                            }
                        });

                        // Apply gaming styles to new dropdowns
                        const newDropdowns = node.querySelectorAll ? node.querySelectorAll('.rounded-md.ring-1.ring-black.ring-opacity-5') : [];
                        newDropdowns.forEach(dropdown => {
                            dropdown.classList.add('gaming-dropdown');
                        });
                        
                        // Apply styling to pagination components
                        const paginationElements = node.querySelectorAll ? node.querySelectorAll('nav[role="navigation"] > div') : [];
                        paginationElements.forEach(elem => {
                            if (elem.parentElement && !elem.parentElement.parentElement.classList.contains('pagination')) {
                                elem.parentElement.parentElement.classList.add('pagination');
                            }
                        });
                    }
                });
            });
        });

        // Start observing
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
        
        // Apply enhanced gaming card effects
        function enhanceGameCards() {
            const gameCards = document.querySelectorAll('.gaming-card');
            gameCards.forEach((card, index) => {
                // Skip cards that are already enhanced
                if (card.dataset.enhanced === 'true') return;
                
                // Mark as enhanced to avoid duplicate processing
                card.dataset.enhanced = 'true';
                
                // Add subtle glow effect on hover
                card.addEventListener('mouseenter', () => {
                    const cardColor = getComputedStyle(card).borderColor;
                    card.style.boxShadow = `0 0 30px ${cardColor.replace(')', ', 0.3)')}, 0 0 15px rgba(0, 150, 255, 0.2)`;
                });
                
                card.addEventListener('mouseleave', () => {
                    card.style.boxShadow = '';
                });
                
                // Add subtle animation delay for staggered appearance
                if (!card.style.animationDelay) {
                    card.style.animationDelay = `${index * 0.1}s`;
                }
            });
        }
        
        // Call initially and set interval to catch dynamically loaded content
        enhanceGameCards();
        setInterval(enhanceGameCards, 1000);
        
        // Create particle effect on interaction
        document.addEventListener('click', function(e) {
            // Check if click is on a gaming-themed element
            const isGamingElement = e.target.closest('.gaming-card, .btn-gaming-primary, .btn-gaming-secondary, button');
            if (isGamingElement) {
                createInteractionParticles(e.clientX, e.clientY);
            }
        });
        
        function createInteractionParticles(x, y) {
            const particlesContainer = document.getElementById('particles');
            if (!particlesContainer) return;
            
            // Create 5-8 particles
            const count = Math.floor(Math.random() * 4) + 5;
            
            for (let i = 0; i < count; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');
                
                // Position at click location
                particle.style.left = `${x}px`;
                particle.style.top = `${y}px`;
                
                // Randomize size (slightly larger than normal particles)
                const size = Math.random() * 4 + 2;
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                
                // Blue-cyan theme colors with higher intensity
                const hue = Math.random() * 40 + 180; // 180-220 range for blues and cyans
                const saturation = Math.random() * 20 + 80; // 80-100%
                const lightness = Math.random() * 20 + 70; // 70-90%
                particle.style.background = `hsl(${hue}, ${saturation}%, ${lightness}%)`;
                particle.style.boxShadow = `0 0 ${size * 4}px hsl(${hue}, ${saturation}%, ${lightness}%)`;
                
                // Custom animation for click particles
                particle.style.animation = 'none';
                const angle = Math.random() * Math.PI * 2; // Random direction
                const speed = Math.random() * 50 + 50; // Random speed
                const tx = Math.cos(angle) * speed;
                const ty = Math.sin(angle) * speed;
                
                // Apply custom animation
                particle.animate([
                    { transform: 'translate(0, 0) scale(1)', opacity: 1 },
                    { transform: `translate(${tx}px, ${ty}px) scale(0)`, opacity: 0 }
                ], {
                    duration: Math.random() * 1000 + 500, // 500-1500ms
                    easing: 'cubic-bezier(0.1, 0.8, 0.2, 1)'
                });
                
                // Add to container
                particlesContainer.appendChild(particle);
                
                // Remove after animation
                setTimeout(() => {
                    if (particle.parentNode) {
                        particle.remove();
                    }
                }, 1500);
            }
        }
        
        // Scroll progress indicator
        window.addEventListener('scroll', function() {
            const scrollProgress = document.getElementById('scrollProgress');
            if (!scrollProgress) return;
            
            const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrolled = (winScroll / height) * 100;
            
            scrollProgress.style.width = scrolled + '%';
            
            // Add glow effect when scrolling
            scrollProgress.style.boxShadow = '0 0 15px rgba(0, 150, 255, 0.8)';
            
            // Reset glow after scrolling stops
            clearTimeout(window.scrollTimeout);
            window.scrollTimeout = setTimeout(() => {
                scrollProgress.style.boxShadow = '0 0 10px rgba(0, 150, 255, 0.6)';
            }, 300);
        });
    </script>
</body>
</html>