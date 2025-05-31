{{-- resources/views/seller/sales.blade.php --}}
<x-layouts.app>
    <x-slot name="header">
        Sales & Analytics
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <livewire:seller.sales-activity />
        </div>
    </div>
</x-layouts.app>