<x-layouts.app>
    <x-slot name="header">
        <h2 class="font-semibold text-xl neon-text-gradient">
            {{ __('Profile Settings') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Profile Header -->
            <div class="gaming-card p-6 rounded-2xl glow-border mb-8">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-cyan-400 rounded-full flex items-center justify-center float-animation">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold neon-text-blue">Profile Management</h1>
                        <p class="text-gaming-secondary text-lg">Manage your account settings and security preferences</p>
                    </div>
                </div>
            </div>

            <!-- Profile Forms Container -->
            <div class="space-y-8">
            @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                <div class="gaming-card p-8 rounded-2xl">
                    @livewire('profile.update-profile-information-form')
                </div>
            @endif

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <div class="gaming-card p-8 rounded-2xl">
                    @livewire('profile.update-password-form')
                </div>
            @endif

            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <div class="gaming-card p-8 rounded-2xl">
                    @livewire('profile.two-factor-authentication-form')
                </div>
            @endif

            <div class="gaming-card p-8 rounded-2xl">
                @livewire('profile.logout-other-browser-sessions-form')
            </div>

            @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                <div class="gaming-card p-8 rounded-2xl border-red-500/30">
                    @livewire('profile.delete-user-form')
                </div>
            @endif
            </div>
        </div>
    </div>
</x-layouts.app>
