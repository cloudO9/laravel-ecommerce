<div class="py-8 px-4">
    <div class="max-w-2xl mx-auto">
        <!-- Compact Header -->
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-12 h-12 bg-gradient-to-r from-emerald-500 to-cyan-500 rounded-xl mb-3">
                <span class="text-xl">🎮</span>
            </div>
            <h2 class="text-2xl font-bold bg-gradient-to-r from-emerald-400 to-cyan-400 bg-clip-text text-transparent mb-2">
                Post a New Game
            </h2>
            <p class="text-slate-400 text-sm">Add your game to the marketplace</p>
        </div>

        <!-- Compact Form Card -->
        <div class="bg-slate-800/80 backdrop-blur-lg border border-slate-700/50 rounded-2xl shadow-xl p-6">
            <!-- Success Message -->
            @if (session()->has('message'))
                <div class="bg-emerald-500/20 border border-emerald-500/40 text-emerald-300 px-4 py-3 rounded-lg mb-4 text-sm">
                    <span class="mr-2">✅</span>{{ session('message') }}
                </div>
            @endif

            <!-- Error Message -->
            @if (session()->has('error'))
                <div class="bg-red-500/20 border border-red-500/40 text-red-300 px-4 py-3 rounded-lg mb-4 text-sm">
                    <span class="mr-2">⚠️</span>{{ session('error') }}
                </div>
            @endif

            <form wire:submit.prevent="postGame" enctype="multipart/form-data" class="space-y-4">
                
                <!-- Game Name -->
                <div>
                    <label for="name" class="flex items-center text-sm font-medium text-slate-200 mb-2">
                        <span class="w-5 h-5 bg-gradient-to-r from-amber-500 to-orange-500 rounded text-xs flex items-center justify-center mr-2">🏷️</span>
                        Game Name *
                    </label>
                    <input type="text" wire:model.defer="name" id="name"
                        class="w-full px-4 py-2.5 bg-slate-900/60 border border-slate-600/50 rounded-lg text-white
                               placeholder-slate-400 text-sm
                               focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500/40
                               transition-all duration-200" 
                        placeholder="Enter game name..." />
                    @error('name') 
                        <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Game Image -->
                <div>
                    <label for="image" class="flex items-center text-sm font-medium text-slate-200 mb-2">
                        <span class="w-5 h-5 bg-gradient-to-r from-purple-500 to-pink-500 rounded text-xs flex items-center justify-center mr-2">📷</span>
                        Game Image * <span class="text-slate-500 text-xs ml-1">(Max 2MB)</span>
                    </label>
                    
                    <div class="relative">
                        <input type="file" wire:model="image" id="image" accept="image/*"
                            class="w-full px-4 py-2.5 bg-slate-900/60 border border-slate-600/50 rounded-lg text-white text-sm
                                   file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 
                                   file:bg-emerald-600 file:text-white file:text-xs file:font-medium
                                   hover:file:bg-emerald-700 file:transition-colors
                                   focus:outline-none focus:ring-2 focus:ring-emerald-500/40" />
                    </div>
                    @error('image') 
                        <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span>
                    @enderror

                    @if ($image)
                        <div class="mt-3 p-3 bg-slate-900/40 rounded-lg border border-slate-700/30">
                            <p class="text-emerald-300 text-xs mb-2 flex items-center">
                                <span class="mr-1">✨</span> Preview:
                            </p>
                            <img src="{{ $image->temporaryUrl() }}" alt="Preview" 
                                 class="w-24 h-24 rounded-lg object-cover border border-emerald-500/30" />
                        </div>
                    @endif
                </div>

                <!-- FIXED: Clear Rent vs Sell Selection -->
                <div class="bg-slate-900/40 rounded-lg p-4 border border-slate-700/30">
                    <h3 class="text-sm font-medium text-slate-200 mb-3 flex items-center">
                        <span class="w-5 h-5 bg-gradient-to-r from-blue-500 to-purple-500 rounded text-xs flex items-center justify-center mr-2">🎯</span>
                        How do you want to list this game? *
                    </h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <!-- FOR SALE Option -->
                        <div class="relative">
                            <input type="radio" wire:model.live="is_for_rent" value="0" id="for_sale"
                                   class="sr-only peer" />
                            <label for="for_sale" class="flex items-center p-4 bg-slate-800/60 border border-slate-600/50 rounded-lg cursor-pointer
                                                     peer-checked:border-emerald-500/60 peer-checked:bg-emerald-500/10 
                                                     hover:border-slate-500/60 transition-all duration-200">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-gradient-to-r from-emerald-500 to-green-500 rounded-lg flex items-center justify-center">
                                        <span class="text-sm">💰</span>
                                    </div>
                                    <div>
                                        <div class="text-white font-medium">For Sale</div>
                                        <div class="text-slate-400 text-xs">Permanent ownership</div>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <!-- FOR RENT Option -->
                        <div class="relative">
                            <input type="radio" wire:model.live="is_for_rent" value="1" id="for_rent"
                                   class="sr-only peer" />
                            <label for="for_rent" class="flex items-center p-4 bg-slate-800/60 border border-slate-600/50 rounded-lg cursor-pointer
                                                    peer-checked:border-purple-500/60 peer-checked:bg-purple-500/10 
                                                    hover:border-slate-500/60 transition-all duration-200">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-indigo-500 rounded-lg flex items-center justify-center">
                                        <span class="text-sm">📅</span>
                                    </div>
                                    <div>
                                        <div class="text-white font-medium">For Rent</div>
                                        <div class="text-slate-400 text-xs">Temporary rental</div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Current Selection Display -->
                    <div class="mt-3 p-3 bg-slate-800/40 rounded-lg">
                        <p class="text-slate-300 text-sm flex items-center gap-2">
                            @if($is_for_rent == 1)
                                <span class="text-purple-400">📅</span>
                                <span>You're listing this game <strong class="text-purple-400">for rent</strong> - buyers can rent it per day</span>
                            @elseif($is_for_rent == 0)
                                <span class="text-emerald-400">💰</span>
                                <span>You're listing this game <strong class="text-emerald-400">for sale</strong> - buyers can purchase it permanently</span>
                            @else
                                <span class="text-slate-400">🤔</span>
                                <span>Please select whether you want to sell or rent this game</span>
                            @endif
                        </p>
                    </div>

                    <!-- Debug Info (Remove this after testing) -->
                    <div class="mt-2 p-2 bg-yellow-500/10 rounded text-yellow-300 text-xs">
                        <strong>Debug:</strong> is_for_rent = {{ var_export($is_for_rent, true) }}
                    </div>
                </div>

                <!-- FIXED: Dynamic Price Section -->
                @if($is_for_rent == 1)
                    <!-- RENT PRICE -->
                    <div>
                        <label for="rent_price" class="flex items-center text-sm font-medium text-slate-200 mb-2">
                            <span class="w-5 h-5 bg-gradient-to-r from-purple-500 to-indigo-500 rounded text-xs flex items-center justify-center mr-2">📅</span>
                            Daily Rent Price *
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-purple-400 text-sm font-medium">$</span>
                            <input type="number" wire:model.defer="rent_price" id="rent_price" min="0.01" step="0.01" max="999.99"
                                class="w-full pl-8 pr-16 py-2.5 bg-slate-900/60 border border-slate-600/50 rounded-lg text-white text-sm
                                       placeholder-slate-400
                                       focus:outline-none focus:ring-2 focus:ring-purple-500/40 focus:border-purple-500/40
                                       transition-all duration-200" 
                                placeholder="0.00" />
                            <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-slate-400 text-xs">/day</span>
                        </div>
                        <p class="text-slate-400 text-xs mt-1">💡 How much should buyers pay per day to rent your game?</p>
                        @error('rent_price') 
                            <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                @elseif($is_for_rent == 0)
                    <!-- SELL PRICE -->
                    <div>
                        <label for="sell_price" class="flex items-center text-sm font-medium text-slate-200 mb-2">
                            <span class="w-5 h-5 bg-gradient-to-r from-emerald-500 to-green-500 rounded text-xs flex items-center justify-center mr-2">💰</span>
                            Sale Price *
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-emerald-400 text-sm font-medium">$</span>
                            <input type="number" wire:model.defer="sell_price" id="sell_price" min="0.01" step="0.01" max="9999.99"
                                class="w-full pl-8 pr-4 py-2.5 bg-slate-900/60 border border-slate-600/50 rounded-lg text-white text-sm
                                       placeholder-slate-400
                                       focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500/40
                                       transition-all duration-200" 
                                placeholder="0.00" />
                        </div>
                        <p class="text-slate-400 text-xs mt-1">💡 How much should buyers pay to own your game permanently?</p>
                        @error('sell_price') 
                            <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>  
                @endif

                <!-- Condition -->
                <div>
                    <label for="condition" class="flex items-center text-sm font-medium text-slate-200 mb-2">
                        <span class="w-5 h-5 bg-gradient-to-r from-yellow-500 to-orange-500 rounded text-xs flex items-center justify-center mr-2">⭐</span>
                        Condition *
                    </label>
                    <div class="relative">
                        <select wire:model.defer="condition" id="condition"
                            class="w-full px-4 py-2.5 bg-slate-900/60 border border-slate-600/50 rounded-lg text-white text-sm
                                   appearance-none cursor-pointer
                                   focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500/40
                                   transition-all duration-200">
                            <option value="" class="bg-slate-800">Select condition...</option>
                            <option value="New" class="bg-slate-800">🆕 New - Never used</option>
                            <option value="Like New" class="bg-slate-800">✨ Like New - Barely used</option>
                            <option value="Very Good" class="bg-slate-800">👍 Very Good - Minor wear</option>
                            <option value="Good" class="bg-slate-800">👌 Good - Some wear</option>
                            <option value="Fair" class="bg-slate-800">⚖️ Fair - Noticeable wear</option>
                            <option value="Poor" class="bg-slate-800">📉 Poor - Heavy wear</option>
                        </select>
                        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                    @error('condition') 
                        <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="flex items-center text-sm font-medium text-slate-200 mb-2">
                        <span class="w-5 h-5 bg-gradient-to-r from-indigo-500 to-purple-500 rounded text-xs flex items-center justify-center mr-2">📝</span>
                        Description 
                        <span class="text-slate-400 text-xs ml-1">(optional, max 1000 chars)</span>
                    </label>
                    <textarea wire:model.defer="description" id="description" rows="3"
                        class="w-full px-4 py-2.5 bg-slate-900/60 border border-slate-600/50 rounded-lg text-white text-sm
                               placeholder-slate-400 resize-none
                               focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500/40
                               transition-all duration-200" 
                        placeholder="Brief description of your game's features, condition, or special notes..."
                        maxlength="1000"></textarea>
                    @error('description') 
                        <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-emerald-600 to-emerald-500 
                               hover:from-emerald-700 hover:to-emerald-600
                               text-white font-semibold py-3 px-6 rounded-lg text-sm
                               transform hover:-translate-y-0.5 hover:shadow-lg hover:shadow-emerald-500/20
                               transition-all duration-200 
                               focus:outline-none focus:ring-2 focus:ring-emerald-500/40
                               disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none
                               flex items-center justify-center gap-2">
                        <span>🚀</span>
                        @if($is_for_rent == 1)
                            List Game for Rent
                        @elseif($is_for_rent == 0)
                            List Game for Sale
                        @else
                            Post Game to Marketplace
                        @endif
                        <span>🎯</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Compact Footer -->
        <div class="text-center mt-4">
            <p class="text-slate-500 text-xs">🎮 GameHub Marketplace</p>
        </div>
    </div>
</div>