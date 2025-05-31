<x-layouts.app>
    <x-slot name="header">
        Game Details
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <livewire:buyer.game-detail :gameId="$gameId" />
        </div>
    </div>
</x-layouts.app>