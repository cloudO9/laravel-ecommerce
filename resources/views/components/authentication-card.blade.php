<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative z-10">
    <!-- Gaming Logo Section with Enhanced Effects -->
    <div class="text-center mb-8 floating-glow relative">
        <!-- Orbital Rings -->
        <div class="orbital-rings"></div>
        
        <div class="w-24 h-24 mx-auto mb-6 relative pulse-rainbow rounded-2xl flex items-center justify-center" 
             style="background: linear-gradient(135deg, #ff00ff, #00ffff);">
            {{ $logo }}
        </div>
        <h1 class="text-4xl font-bold gaming-gradient-text mb-3">
            GameHub
        </h1>
        <p class="neon-text-cyan text-lg">Gaming Authentication</p>
    </div>

    <!-- Enhanced Gaming Card -->
    <div class="w-full sm:max-w-md px-6 py-8 glass-card rounded-2xl shadow-2xl relative overflow-hidden">
        <!-- Enhanced background glow effect -->
        <div class="absolute inset-0 bg-gradient-to-br from-purple-900/20 via-transparent to-cyan-900/20 rounded-2xl"></div>
        <div class="relative z-10">
            {{ $slot }}
        </div>
    </div>
    
    <!-- Back to Home Link -->
    <div class="text-center mt-6">
        <a href="{{ url('/') }}" 
           class="gaming-link text-sm transition-colors duration-200 flex items-center justify-center gap-2">
            <span>←</span> Back to GameHub
        </a>
    </div>
</div>

<style>
/* Additional Gaming Styles for Authentication Card */
.orbital-rings {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 120px;
    height: 120px;
    pointer-events: none;
    z-index: -1;
}

.orbital-rings::before,
.orbital-rings::after {
    content: '';
    position: absolute;
    border: 2px solid transparent;
    border-top-color: rgba(255, 0, 255, 0.3);
    border-right-color: rgba(0, 255, 255, 0.3);
    border-radius: 50%;
    animation: orbital-spin 8s linear infinite;
}

.orbital-rings::before {
    width: 100%;
    height: 100%;
    animation-direction: normal;
}

.orbital-rings::after {
    width: 80%;
    height: 80%;
    top: 10%;
    left: 10%;
    animation-direction: reverse;
    animation-duration: 6s;
}

@keyframes orbital-spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.glass-card {
    background: rgba(15, 20, 40, 0.85) !important;
    backdrop-filter: blur(20px) !important;
    border: 2px solid rgba(255, 0, 255, 0.2) !important;
    box-shadow: 
        0 25px 50px -12px rgba(0, 0, 0, 0.8),
        0 0 50px rgba(255, 0, 255, 0.1),
        inset 0 1px 0 rgba(255, 255, 255, 0.1) !important;
}

.glass-card:hover {
    border-color: rgba(255, 0, 255, 0.4) !important;
    box-shadow: 
        0 25px 50px -12px rgba(0, 0, 0, 0.9),
        0 0 80px rgba(255, 0, 255, 0.2),
        0 0 120px rgba(0, 255, 255, 0.1),
        inset 0 1px 0 rgba(255, 255, 255, 0.2) !important;
}

.gaming-gradient-text {
    background: linear-gradient(135deg, #ff00ff, #00ffff, #ff00ff) !important;
    background-size: 200% 100% !important;
    background-clip: text !important;
    -webkit-background-clip: text !important;
    -webkit-text-fill-color: transparent !important;
    animation: gradient-shift 3s ease infinite !important;
}

.neon-text-cyan {
    color: #00ffff !important;
    text-shadow: 
        0 0 10px rgba(0, 255, 255, 0.6),
        0 0 20px rgba(0, 255, 255, 0.4),
        0 0 30px rgba(0, 255, 255, 0.2) !important;
}

.floating-glow {
    animation: floating-glow 6s ease-in-out infinite !important;
}

@keyframes floating-glow {
    0%, 100% { 
        transform: translateY(0px);
        filter: drop-shadow(0 0 20px rgba(255, 0, 255, 0.4));
    }
    50% { 
        transform: translateY(-10px);
        filter: drop-shadow(0 0 30px rgba(0, 255, 255, 0.6));
    }
}

.pulse-rainbow {
    animation: pulse-rainbow 4s ease-in-out infinite !important;
}

@keyframes pulse-rainbow {
    0%, 100% { 
        box-shadow: 
            0 0 20px rgba(255, 0, 255, 0.6),
            0 0 40px rgba(255, 0, 255, 0.4);
    }
    50% { 
        box-shadow: 
            0 0 30px rgba(0, 255, 255, 0.8),
            0 0 60px rgba(0, 255, 255, 0.5);
    }
}

@keyframes gradient-shift {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
}

.gaming-link {
    color: rgba(0, 255, 255, 0.8) !important;
    text-shadow: 0 0 10px rgba(0, 255, 255, 0.4) !important;
    transition: all 0.3s ease !important;
}

.gaming-link:hover {
    color: #00ffff !important;
    text-shadow: 
        0 0 15px rgba(0, 255, 255, 0.8),
        0 0 25px rgba(0, 255, 255, 0.5) !important;
    transform: translateY(-1px) !important;
}
</style>
