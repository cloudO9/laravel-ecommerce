<x-layouts.app>
    <x-slot name="header">
        Game Details
    </x-slot>    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="gaming-card p-8 rounded-2xl glow-border">
                <!-- Game Detail Header -->
                <div class="mb-6 text-center">
                    <h2 class="text-2xl font-bold neon-text-blue mb-2">Game Showcase</h2>
                    <p class="text-gaming-secondary">Explore game details and features</p>
                </div>
                
                <!-- Game Detail Component -->
                <livewire:buyer.game-detail :gameId="$gameId" />
            </div>
        </div>
    </div>
</x-layouts.app>