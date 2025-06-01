<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'GameHub') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles

    <style>
        body {
            background: linear-gradient(135deg, #0f0f23 0%, #1a1a2e 50%, #16213e 100%);
            background-attachment: fixed;
            min-height: 100vh;
        }

        /* Gaming particles background */
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

        /* Gaming theme overrides with intense effects matching login page */
        .auth-card {
            background: rgba(15, 20, 40, 0.85) !important;
            backdrop-filter: blur(20px) !important;
            border: 2px solid rgba(255, 0, 255, 0.2) !important;
            box-shadow: 
                0 25px 50px -12px rgba(0, 0, 0, 0.8),
                0 0 50px rgba(255, 0, 255, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.1) !important;
        }

        .gaming-input {
            background: rgba(15, 20, 40, 0.8) !important;
            border: 2px solid rgba(255, 0, 255, 0.3) !important;
            color: #fff !important;
            transition: all 0.3s ease !important;
            border-radius: 0.5rem !important;
            padding: 0.75rem 1rem !important;
        }

        .gaming-input:focus {
            border-color: rgba(255, 0, 255, 0.8) !important;
            box-shadow: 
                0 0 0 3px rgba(255, 0, 255, 0.2),
                0 0 20px rgba(255, 0, 255, 0.4),
                0 0 40px rgba(0, 255, 255, 0.2) !important;
            outline: none !important;
            transform: scale(1.02) !important;
        }

        .gaming-input::placeholder {
            color: rgba(255, 255, 255, 0.4) !important;
        }

        .gaming-label {
            color: #fff !important;
            font-weight: 600 !important;
            text-shadow: 0 0 10px rgba(255, 0, 255, 0.4) !important;
            margin-bottom: 0.5rem !important;
            display: block !important;
        }

        .gaming-button {
            background: linear-gradient(135deg, #ff00ff, #00ffff) !important;
            border: none !important;
            color: #000 !important;
            font-weight: 600 !important;
            position: relative !important;
            overflow: hidden !important;
            transition: all 0.3s ease !important;
            border-radius: 0.5rem !important;
            padding: 0.75rem 1.5rem !important;
        }

        .gaming-button::before {
            content: '' !important;
            position: absolute !important;
            top: 0 !important;
            left: -100% !important;
            width: 100% !important;
            height: 100% !important;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent) !important;
            transition: left 0.5s ease !important;
        }

        .gaming-button:hover::before {
            left: 100% !important;
        }

        .gaming-button:hover {
            transform: translateY(-2px) scale(1.02) !important;
            box-shadow: 
                0 10px 25px rgba(255, 0, 255, 0.4),
                0 0 50px rgba(255, 0, 255, 0.3),
                0 0 80px rgba(0, 255, 255, 0.2) !important;
        }

        .gaming-link {
            color: rgba(0, 255, 255, 0.8) !important;
            text-shadow: 0 0 10px rgba(0, 255, 255, 0.4) !important;
            transition: all 0.3s ease !important;
            text-decoration: none !important;
        }

        .gaming-link:hover {
            color: #00ffff !important;
            text-shadow: 
                0 0 15px rgba(0, 255, 255, 0.8),
                0 0 25px rgba(0, 255, 255, 0.5) !important;
            transform: translateY(-1px) !important;
        }

        /* Enhanced text styles for all content */
        .text-gray-600, .text-gray-500, .text-gray-400 {
            color: rgba(255, 255, 255, 0.7) !important;
        }

        .text-green-600 {
            color: #00ffff !important;
            text-shadow: 0 0 10px rgba(0, 255, 255, 0.5) !important;
        }

        .text-red-500, .text-red-600 {
            color: #ff4444 !important;
            text-shadow: 0 0 10px rgba(255, 68, 68, 0.5) !important;
        }

        .text-white {
            color: #fff !important;
        }

        /* Dark theme overrides */
        .bg-gray-100 {
            background: transparent !important;
        }

        .bg-white {
            background: rgba(15, 20, 40, 0.85) !important;
            backdrop-filter: blur(20px) !important;
            border: 2px solid rgba(255, 0, 255, 0.2) !important;
        }

        /* Checkbox styling */
        input[type="checkbox"] {
            background: rgba(15, 20, 40, 0.8) !important;
            border: 2px solid rgba(255, 0, 255, 0.3) !important;
            border-radius: 0.25rem !important;
        }

        input[type="checkbox"]:checked {
            background: linear-gradient(135deg, #ff00ff, #00ffff) !important;
            border-color: rgba(255, 0, 255, 0.8) !important;
        }

        input[type="checkbox"]:focus {
            box-shadow: 
                0 0 0 3px rgba(255, 0, 255, 0.2),
                0 0 20px rgba(255, 0, 255, 0.4) !important;
        }

        /* Status and error message styling */
        .font-medium.text-sm.text-green-600 {
            background: rgba(0, 255, 255, 0.1) !important;
            border: 1px solid rgba(0, 255, 255, 0.3) !important;
            padding: 0.75rem !important;
            border-radius: 0.5rem !important;
            color: #00ffff !important;
            text-shadow: 0 0 10px rgba(0, 255, 255, 0.5) !important;
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
    </style>
</head>
<body class="font-sans text-white antialiased">
    <!-- Animated Background Particles -->
    <div class="particles-bg" id="particles"></div>

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative z-10">
        {{ $slot }}
    </div>

    @livewireScripts

    <!-- Particles Animation Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const particlesContainer = document.getElementById('particles');
            const particleCount = window.innerWidth > 768 ? 30 : 15;

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

            // Apply gaming styles to form elements
            setTimeout(() => {
                // Style inputs
                const inputs = document.querySelectorAll('input:not([type="checkbox"])');
                inputs.forEach(input => {
                    input.classList.add('gaming-input');
                });

                // Style labels
                const labels = document.querySelectorAll('label');
                labels.forEach(label => {
                    if (!label.querySelector('input[type="checkbox"]')) {
                        label.classList.add('gaming-label');
                    }
                });

                // Style buttons
                const buttons = document.querySelectorAll('button, .button');
                buttons.forEach(button => {
                    button.classList.add('gaming-button');
                });

                // Style links
                const links = document.querySelectorAll('a');
                links.forEach(link => {
                    link.classList.add('gaming-link');
                });

                // Style select dropdown
                const selects = document.querySelectorAll('select');
                selects.forEach(select => {
                    select.classList.add('gaming-input');
                });

                // Style auth card
                const authCard = document.querySelector('.bg-white');
                if (authCard) {
                    authCard.classList.remove('bg-white');
                    authCard.classList.add('auth-card');
                }
            }, 100);
        });
    </script>
</body>
</html>