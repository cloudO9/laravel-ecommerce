<x-layouts.app>    

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="gaming-card p-8 rounded-2xl glow-border">
                <!-- Purchase History Header -->
                <div class="mb-6 text-center">
                    <h2 class="text-2xl font-bold neon-text-gradient mb-2">Your Game Library</h2>
                    <p class="text-gaming-secondary">View your purchased games and download history</p>
                </div>
                
                <!-- Purchase History Component -->
                <livewire:buyer.purchasehistory />
            </div>
        </div>
    </div>
</x-layouts.app>