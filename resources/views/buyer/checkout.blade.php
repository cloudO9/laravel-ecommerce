<x-layouts.app>
    <x-slot name="header">
        Checkout
    </x-slot>    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="gaming-card p-8 rounded-2xl glow-border">
                <!-- Checkout Header -->
                <div class="mb-6 text-center">
                    <h2 class="text-2xl font-bold neon-text-cyan mb-2">Secure Checkout</h2>
                    <p class="text-gaming-secondary">Complete your game purchase securely</p>
                </div>
                
                <!-- Checkout Component -->
                <livewire:buyer.checkout />
            </div>
        </div>
    </div>
</x-layouts.app>