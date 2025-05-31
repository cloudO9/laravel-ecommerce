{{-- resources/views/auth/register.blade.php --}}
<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center p-4" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
        <!-- Background Pattern -->
        <div class="fixed inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.15) 1px, transparent 0); background-size: 50px 50px;"></div>
        </div>

        <!-- Register Card -->
        <div class="w-full max-w-md relative z-10">
            <!-- Logo Section -->
            <div class="text-center mb-8">
                <div class="w-20 h-20 bg-gradient-to-r from-emerald-500 to-cyan-500 rounded-2xl mx-auto mb-4 flex items-center justify-center">
                    <span class="text-3xl">💿</span>
                </div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-emerald-400 to-cyan-400 bg-clip-text text-transparent mb-2">
                    GameHub
                </h1>
                <p class="text-slate-400">Join the gaming community!</p>
            </div>

            <!-- Register Form -->
            <div style="background: rgba(30, 41, 59, 0.8); backdrop-filter: blur(20px); border: 1px solid rgba(71, 85, 105, 0.3);" 
                 class="rounded-2xl p-8 shadow-2xl">
                
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-bold text-white mb-2">Create Account</h2>
                    <p class="text-slate-400 text-sm">Start your gaming journey</p>
                </div>

                <!-- Validation Errors -->
                <x-validation-errors class="mb-4" />

                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf

                    <!-- Name Field -->
                    <div>
                        <x-label for="name" value="{{ __('Full Name') }}" 
                                class="flex items-center text-sm font-medium text-slate-200 mb-2">
                            <span class="w-5 h-5 bg-gradient-to-r from-blue-500 to-purple-500 rounded text-xs flex items-center justify-center mr-2">👤</span>
                            {{ __('Full Name') }}
                        </x-label>
                        <x-input id="name" 
                                class="w-full px-4 py-3 bg-slate-900/60 border border-slate-600/50 rounded-lg text-white
                                       placeholder-slate-400 text-sm
                                       focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500/40
                                       transition-all duration-200" 
                                type="text" 
                                name="name" 
                                :value="old('name')" 
                                required 
                                autofocus 
                                autocomplete="name" 
                                placeholder="Enter your full name..." />
                    </div>

                    <!-- Email Field -->
                    <div>
                        <x-label for="email" value="{{ __('Email Address') }}" 
                                class="flex items-center text-sm font-medium text-slate-200 mb-2">
                            <span class="w-5 h-5 bg-gradient-to-r from-emerald-500 to-green-500 rounded text-xs flex items-center justify-center mr-2">📧</span>
                            {{ __('Email Address') }}
                        </x-label>
                        <x-input id="email" 
                                class="w-full px-4 py-3 bg-slate-900/60 border border-slate-600/50 rounded-lg text-white
                                       placeholder-slate-400 text-sm
                                       focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500/40
                                       transition-all duration-200" 
                                type="email" 
                                name="email" 
                                :value="old('email')" 
                                required 
                                autocomplete="username" 
                                placeholder="Enter your email..." />
                    </div>

                    <!-- Password Field -->
                    <div>
                        <x-label for="password" value="{{ __('Password') }}" 
                                class="flex items-center text-sm font-medium text-slate-200 mb-2">
                            <span class="w-5 h-5 bg-gradient-to-r from-purple-500 to-indigo-500 rounded text-xs flex items-center justify-center mr-2">🔒</span>
                            {{ __('Password') }}
                        </x-label>
                        <x-input id="password" 
                                class="w-full px-4 py-3 bg-slate-900/60 border border-slate-600/50 rounded-lg text-white
                                       placeholder-slate-400 text-sm
                                       focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500/40
                                       transition-all duration-200" 
                                type="password" 
                                name="password" 
                                required 
                                autocomplete="new-password" 
                                placeholder="Create a password..." />
                    </div>

                    <!-- Confirm Password Field -->
                    <div>
                        <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" 
                                class="flex items-center text-sm font-medium text-slate-200 mb-2">
                            <span class="w-5 h-5 bg-gradient-to-r from-pink-500 to-rose-500 rounded text-xs flex items-center justify-center mr-2">🔑</span>
                            {{ __('Confirm Password') }}
                        </x-label>
                        <x-input id="password_confirmation" 
                                class="w-full px-4 py-3 bg-slate-900/60 border border-slate-600/50 rounded-lg text-white
                                       placeholder-slate-400 text-sm
                                       focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500/40
                                       transition-all duration-200" 
                                type="password" 
                                name="password_confirmation" 
                                required 
                                autocomplete="new-password" 
                                placeholder="Confirm your password..." />
                    </div>

                    <!-- Role Selection -->
                    <div>
                        <label for="role" class="flex items-center text-sm font-medium text-slate-200 mb-2">
                            <span class="w-5 h-5 bg-gradient-to-r from-yellow-500 to-orange-500 rounded text-xs flex items-center justify-center mr-2">🎯</span>
                            Register as
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <!-- Buyer Option -->
                            <div class="relative">
                                <input type="radio" name="role" value="buyer" id="buyer" 
                                       class="sr-only peer" {{ old('role', 'buyer') == 'buyer' ? 'checked' : '' }} />
                                <label for="buyer" class="flex items-center p-4 bg-slate-800/60 border border-slate-600/50 rounded-lg cursor-pointer
                                                         peer-checked:border-emerald-500/60 peer-checked:bg-emerald-500/10 
                                                         hover:border-slate-500/60 transition-all duration-200">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-gradient-to-r from-emerald-500 to-green-500 rounded-lg flex items-center justify-center">
                                            <span class="text-sm">🛒</span>
                                        </div>
                                        <div>
                                            <div class="text-white font-medium">Buyer</div>
                                            <div class="text-slate-400 text-xs">Purchase CDs</div>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            <!-- Seller Option -->
                            <div class="relative">
                                <input type="radio" name="role" value="seller" id="seller" 
                                       class="sr-only peer" {{ old('role') == 'seller' ? 'checked' : '' }} />
                                <label for="seller" class="flex items-center p-4 bg-slate-800/60 border border-slate-600/50 rounded-lg cursor-pointer
                                                          peer-checked:border-purple-500/60 peer-checked:bg-purple-500/10 
                                                          hover:border-slate-500/60 transition-all duration-200">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-indigo-500 rounded-lg flex items-center justify-center">
                                            <span class="text-sm">💰</span>
                                        </div>
                                        <div>
                                            <div class="text-white font-medium">Seller</div>
                                            <div class="text-slate-400 text-xs">Sell CDs</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Terms and Privacy Policy -->
                    @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                        <div>
                            <label for="terms" class="flex items-start cursor-pointer">
                                <x-checkbox name="terms" 
                                           id="terms" 
                                           required
                                           class="w-4 h-4 text-emerald-600 bg-slate-900/60 border-slate-600/50 rounded 
                                                  focus:ring-emerald-500/40 focus:ring-2 mt-1" />
                                <div class="ml-3">
                                    <div class="text-sm text-slate-300">
                                        {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                                'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="text-emerald-400 hover:text-emerald-300 underline transition-colors duration-200">'.__('Terms of Service').'</a>',
                                                'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="text-emerald-400 hover:text-emerald-300 underline transition-colors duration-200">'.__('Privacy Policy').'</a>',
                                        ]) !!}
                                    </div>
                                </div>
                            </label>
                        </div>
                    @endif

                    <!-- Submit Button -->
                    <x-button class="w-full bg-gradient-to-r from-emerald-600 to-emerald-500 
                                    hover:from-emerald-700 hover:to-emerald-600
                                    text-white font-semibold py-3 px-6 rounded-lg
                                    transform hover:-translate-y-0.5 hover:shadow-lg hover:shadow-emerald-500/20
                                    transition-all duration-200 
                                    focus:outline-none focus:ring-2 focus:ring-emerald-500/40
                                    flex items-center justify-center gap-2">
                        <span>🚀</span>
                        {{ __('Create Account') }}
                        <span>🎮</span>
                    </x-button>
                </form>

                <!-- Login Link -->
                <div class="mt-6 text-center">
                    <p class="text-slate-400 text-sm">
                        Already have an account? 
                        <a href="{{ route('login') }}" 
                           class="text-emerald-400 hover:text-emerald-300 font-medium transition-colors duration-200">
                            Sign in here
                        </a>
                    </p>
                </div>
            </div>

            <!-- Back to Home -->
            <div class="text-center mt-6">
                <a href="{{ url('/') }}" 
                   class="text-slate-400 hover:text-emerald-400 text-sm transition-colors duration-200 flex items-center justify-center gap-2">
                    <span>←</span> Back to GameHub
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>