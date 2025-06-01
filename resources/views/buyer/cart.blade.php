<x-layouts.app>
  <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="gaming-card p-8 rounded-2xl glow-border">
                <!-- Cart Header -->
                <div class="mb-6 text-center">
                    <h2 class="text-2xl font-bold neon-text-blue mb-2">Your Gaming Cart</h2>
                    <p class="text-gaming-secondary">Review your selected games before checkout</p>
                </div>
                
                <!-- Cart Component -->
                <livewire:buyer.cart />
            </div>
        </div>
    </div>
</x-layouts.app>