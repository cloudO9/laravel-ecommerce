<x-layouts.app>
    <x-slot name="header">
        <h2 class="font-semibold text-xl neon-text-gradient">
            {{ __('Browse Games') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Gaming Wrapper with Enhanced Effects -->
            <div class="relative z-10 overflow-hidden">
                <!-- Animated Background Elements -->
                <div class="absolute -top-20 -right-20 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-cyan-500/10 rounded-full blur-3xl"></div>
                
                <!-- Main Content -->
                <livewire:buyer.dashboard />
            </div>
        </div>
    </div>
</x-layouts.app>