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

        /* Gaming theme overrides */
        .auth-card {
            background: rgba(26, 26, 46, 0.9);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(0, 255, 136, 0.2);
        }

        .gaming-input {
            background: rgba(15, 15, 35, 0.8) !important;
            border: 1px solid rgba(0, 255, 136, 0.3) !important;
            color: #fff !important;
        }

        .gaming-input:focus {
            border-color: rgba(0, 255, 136, 0.6) !important;
            box-shadow: 0 0 0 3px rgba(0, 255, 136, 0.1) !important;
            outline: none !important;
        }

        .gaming-label {
            color: #00ff88 !important;
            font-weight: 600;
        }

        .gaming-button {
            background: linear-gradient(45deg, #00ff88, #00cc6a) !important;
            color: #000 !important;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .gaming-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 255, 136, 0.3);
        }

        .gaming-link {
            color: #00ff88 !important;
        }

        .gaming-link:hover {
            color: #00cc6a !important;
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