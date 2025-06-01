<div class="min-h-screen py-12 px-4">
    <div class="max-w-4xl mx-auto">
        <!-- Enhanced Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-emerald-500 to-cyan-500 rounded-3xl mb-6 shadow-2xl shadow-emerald-500/30">
                <span class="text-3xl">🎮</span>
            </div>
            <h1 class="text-4xl font-bold bg-gradient-to-r from-emerald-400 to-cyan-400 bg-clip-text text-transparent mb-4">
                Post a New Game
            </h1>
            <p class="text-slate-400 text-lg">Add your game to the marketplace and start earning</p>
        </div>

        <!-- Enhanced Form Card -->
        <div class="bg-slate-800/70 backdrop-blur-xl border border-slate-700/50 rounded-3xl shadow-2xl shadow-slate-900/50 p-8 md:p-10">
            <!-- Success Message -->
            @if (session()->has('message'))
                <div class="bg-emerald-500/20 border border-emerald-500/40 text-emerald-300 px-6 py-4 rounded-xl mb-8 text-sm flex items-center shadow-lg shadow-emerald-500/20">
                    <span class="text-2xl mr-3"></span>{{ session('message') }}
                </div>
            @endif

            <!-- Error Message -->
            @if (session()->has('error'))
                <div class="bg-red-500/20 border border-red-500/40 text-red-300 px-6 py-4 rounded-xl mb-8 text-sm flex items-center shadow-lg shadow-red-500/20">
                    <span class="text-2xl mr-3"></span>{{ session('error') }}
                </div>
            @endif

            <form wire:submit.prevent="postGame" enctype="multipart/form-data" class="space-y-8">
                
                <!-- Game Name -->
                <div class="space-y-3">
                    <label for="name" class="flex items-center text-lg font-semibold text-white">
                        <span class="w-8 h-8 bg-gradient-to-r from-amber-500 to-orange-500 rounded-xl text-sm flex items-center justify-center mr-3 shadow-lg">🏷️</span>
                        Game Name
                        <span class="text-red-400 ml-1">*</span>
                    </label>
                    <input type="text" wire:model.defer="name" id="name"
                        class="w-full px-6 py-4 bg-slate-900/60 border border-slate-600/50 rounded-xl text-white text-lg
                               placeholder-slate-400
                               focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500/40
                               transition-all duration-300 shadow-md hover:shadow-lg" 
                        placeholder="Enter the name of your game..." />
                    @error('name') 
                        <span class="text-sm text-red-400 mt-2 block flex items-center gap-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- Game Image -->
                <div class="space-y-4">
                    <label for="image" class="flex items-center text-lg font-semibold text-white">
                        <span class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl text-sm flex items-center justify-center mr-3 shadow-lg">📷</span>
                        Game Image
                        <span class="text-red-400 ml-1">*</span>
                        <span class="text-slate-400 text-sm ml-2 font-normal">(Max 2MB)</span>
                    </label>
                    
                    <div class="relative">
                        <input type="file" wire:model="image" id="image" accept="image/*"
                            class="w-full px-6 py-4 bg-slate-900/60 border border-slate-600/50 rounded-xl text-white
                                   file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 
                                   file:bg-emerald-600 file:text-white file:text-sm file:font-semibold
                                   hover:file:bg-emerald-700 file:transition-colors file:shadow-md
                                   focus:outline-none focus:ring-2 focus:ring-emerald-500/40 shadow-md hover:shadow-lg transition-all duration-300" />
                    </div>
                    @error('image') 
                        <span class="text-sm text-red-400 mt-2 block flex items-center gap-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                            {{ $message }}
                        </span>
                    @enderror

                    @if ($image)
                        <div class="mt-4 p-6 bg-slate-900/40 rounded-xl border border-slate-700/30 shadow-lg">
                            <p class="text-emerald-300 text-sm mb-4 flex items-center font-semibold">
                                <span class="mr-2 text-lg"></span> Image Preview:
                            </p>
                            <div class="flex justify-center">
                                <img src="{{ $image->temporaryUrl() }}" alt="Preview" 
                                     class="w-32 h-32 rounded-xl object-cover border-2 border-emerald-500/30 shadow-lg" />
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Enhanced Rent vs Sell Selection -->
                <div class="bg-slate-900/40 rounded-2xl p-6 border border-slate-700/30 shadow-lg">
                    <h3 class="text-lg font-semibold text-white mb-6 flex items-center">
                        <span class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-xl text-sm flex items-center justify-center mr-3 shadow-lg">🎯</span>
                        How do you want to list this game?
                        <span class="text-red-400 ml-1">*</span>
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- FOR SALE Option -->
                        <div class="relative">
                            <input type="radio" wire:model.live="is_for_rent" value="0" id="for_sale"
                                   class="sr-only peer" />
                            <label for="for_sale" class="flex items-center p-6 bg-slate-800/60 border-2 border-slate-600/50 rounded-xl cursor-pointer
                                                     peer-checked:border-emerald-500/60 peer-checked:bg-emerald-500/10 peer-checked:shadow-xl peer-checked:shadow-emerald-500/20
                                                     hover:border-slate-500/60 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                <div class="flex items-center gap-4 w-full">
                                    <div class="w-12 h-12 bg-gradient-to-r from-emerald-500 to-green-500 rounded-xl flex items-center justify-center shadow-lg">
                                        <span class="text-xl">💰</span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="text-white font-bold text-lg">For Sale</div>
                                        <div class="text-slate-400">Permanent ownership transfer</div>
                                    </div>
                                    <div class="w-6 h-6 border-2 border-slate-500 rounded-full peer-checked:border-emerald-500 peer-checked:bg-emerald-500 flex items-center justify-center">
                                        <div class="w-3 h-3 bg-white rounded-full opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <!-- FOR RENT Option -->
                        <div class="relative">
                            <input type="radio" wire:model.live="is_for_rent" value="1" id="for_rent"
                                   class="sr-only peer" />
                            <label for="for_rent" class="flex items-center p-6 bg-slate-800/60 border-2 border-slate-600/50 rounded-xl cursor-pointer
                                                    peer-checked:border-purple-500/60 peer-checked:bg-purple-500/10 peer-checked:shadow-xl peer-checked:shadow-purple-500/20
                                                    hover:border-slate-500/60 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                <div class="flex items-center gap-4 w-full">
                                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-indigo-500 rounded-xl flex items-center justify-center shadow-lg">
                                        <span class="text-xl">📅</span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="text-white font-bold text-lg">For Rent</div>
                                        <div class="text-slate-400">Temporary rental period</div>
                                    </div>
                                    <div class="w-6 h-6 border-2 border-slate-500 rounded-full peer-checked:border-purple-500 peer-checked:bg-purple-500 flex items-center justify-center">
                                        <div class="w-3 h-3 bg-white rounded-full opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Current Selection Display -->
                    <div class="mt-6 p-4 bg-slate-800/40 rounded-xl border border-slate-700/30">
                        <p class="text-slate-300 flex items-center gap-3">
                            @if($is_for_rent == 1)
                                <span class="text-purple-400 text-xl">📅</span>
                                <span>You're listing this game <strong class="text-purple-400">for rent</strong> - buyers can rent it per day</span>
                            @elseif($is_for_rent == 0)
                                <span class="text-emerald-400 text-xl">💰</span>
                                <span>You're listing this game <strong class="text-emerald-400">for sale</strong> - buyers can purchase it permanently</span>
                            @else
                                <span class="text-slate-400 text-xl"></span>
                                <span>Please select whether you want to sell or rent this game</span>
                            @endif
                        </p>
                    </div>

                    <!-- Debug Info (Remove this after testing) -->

                <!-- Enhanced Dynamic Price Section -->
                @if($is_for_rent == 1)
                    <!-- RENT PRICE -->
                    <div class="space-y-3">
                        <label for="rent_price" class="flex items-center text-lg font-semibold text-white">
                            <span class="w-8 h-8 bg-gradient-to-r from-purple-500 to-indigo-500 rounded-xl text-sm flex items-center justify-center mr-3 shadow-lg">📅</span>
                            Daily Rent Price
                            <span class="text-red-400 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-6 top-1/2 transform -translate-y-1/2 text-purple-400 text-lg font-bold">$</span>
                            <input type="number" wire:model.defer="rent_price" id="rent_price" min="0.01" step="0.01" max="999.99"
                                class="w-full pl-12 pr-20 py-4 bg-slate-900/60 border border-slate-600/50 rounded-xl text-white text-lg
                                       placeholder-slate-400
                                       focus:outline-none focus:ring-2 focus:ring-purple-500/40 focus:border-purple-500/40
                                       transition-all duration-300 shadow-md hover:shadow-lg" 
                                placeholder="0.00" />
                            <span class="absolute right-6 top-1/2 transform -translate-y-1/2 text-slate-400 font-semibold">/day</span>
                        </div>
                        <p class="text-slate-400 flex items-center gap-2 text-sm">
                            <span class="text-lg"></span>
                            How much should buyers pay per day to rent your game?
                        </p>
                        @error('rent_price') 
                            <span class="text-sm text-red-400 mt-2 block flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                @elseif($is_for_rent == 0)
                    <!-- SELL PRICE -->
                    <div class="space-y-3">
                        <label for="sell_price" class="flex items-center text-lg font-semibold text-white">
                            <span class="w-8 h-8 bg-gradient-to-r from-emerald-500 to-green-500 rounded-xl text-sm flex items-center justify-center mr-3 shadow-lg">💰</span>
                            Sale Price
                            <span class="text-red-400 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-6 top-1/2 transform -translate-y-1/2 text-emerald-400 text-lg font-bold">$</span>
                            <input type="number" wire:model.defer="sell_price" id="sell_price" min="0.01" step="0.01" max="9999.99"
                                class="w-full pl-12 pr-6 py-4 bg-slate-900/60 border border-slate-600/50 rounded-xl text-white text-lg
                                       placeholder-slate-400
                                       focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500/40
                                       transition-all duration-300 shadow-md hover:shadow-lg" 
                                placeholder="0.00" />
                        </div>
                        <p class="text-slate-400 flex items-center gap-2 text-sm">
                            <span class="text-lg"></span>
                            How much should buyers pay to own your game permanently?
                        </p>
                        @error('sell_price') 
                            <span class="text-sm text-red-400 mt-2 block flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>  
                @endif

                <!-- Enhanced Condition -->
                <div class="space-y-3">
                    <label for="condition" class="flex items-center text-lg font-semibold text-white">
                        <span class="w-8 h-8 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl text-sm flex items-center justify-center mr-3 shadow-lg">⭐</span>
                        Condition
                        <span class="text-red-400 ml-1">*</span>
                    </label>
                    <div class="relative">
                        <select wire:model.defer="condition" id="condition"
                            class="w-full px-6 py-4 bg-slate-900/60 border border-slate-600/50 rounded-xl text-white text-lg
                                   appearance-none cursor-pointer
                                   focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500/40
                                   transition-all duration-300 shadow-md hover:shadow-lg">
                            <option value="" class="bg-slate-800">Select condition...</option>
                            <option value="New" class="bg-slate-800"> New - Never used</option>
                            <option value="Like New" class="bg-slate-800"> Like New - Barely used</option>
                            <option value="Very Good" class="bg-slate-800"> Very Good - Minor wear</option>
                            <option value="Good" class="bg-slate-800"> Good - Some wear</option>
                            <option value="Fair" class="bg-slate-800"> Fair - Noticeable wear</option>
                            <option value="Poor" class="bg-slate-800"> Poor - Heavy wear</option>
                        </select>
                        <div class="absolute inset-y-0 right-6 flex items-center pointer-events-none">
                            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                    @error('condition') 
                        <span class="text-sm text-red-400 mt-2 block flex items-center gap-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- Enhanced Description -->
                <div class="space-y-3">
                    <label for="description" class="flex items-center text-lg font-semibold text-white">
                        <span class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-xl text-sm flex items-center justify-center mr-3 shadow-lg">📝</span>
                        Description 
                        <span class="text-slate-400 text-sm ml-2 font-normal">(optional, max 1000 chars)</span>
                    </label>
                    <textarea wire:model.defer="description" id="description" rows="4"
                        class="w-full px-6 py-4 bg-slate-900/60 border border-slate-600/50 rounded-xl text-white text-lg
                               placeholder-slate-400 resize-none
                               focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500/40
                               transition-all duration-300 shadow-md hover:shadow-lg" 
                        placeholder="Brief description of your game's features, condition, or special notes..."
                        maxlength="1000"></textarea>
                    @error('description') 
                        <span class="text-sm text-red-400 mt-2 block flex items-center gap-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- Enhanced Submit Button -->
                <div class="pt-6">
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-emerald-600 via-emerald-500 to-emerald-600
                               hover:from-emerald-700 hover:via-emerald-600 hover:to-emerald-700
                               text-white font-bold py-5 px-8 rounded-xl text-lg
                               transform hover:-translate-y-1 hover:shadow-xl hover:shadow-emerald-500/30
                               transition-all duration-300 
                               focus:outline-none focus:ring-2 focus:ring-emerald-500/40
                               disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none
                               flex items-center justify-center gap-3 shadow-lg">
                        <span class="text-xl"></span>
                        @if($is_for_rent == 1)
                            List Game for Rent
                        @elseif($is_for_rent == 0)
                            List Game for Sale
                        @else
                            Post Game to Marketplace
                        @endif
                        <span class="text-xl"></span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Enhanced Footer -->
        <div class="text-center mt-8">
            <p class="text-slate-500 flex items-center justify-center gap-2">
                <span class="text-lg">🎮</span>
                <span class="font-medium">GameHub Marketplace</span>
                <span class="text-lg">🌟</span>
            </p>
        </div>
    </div>
</div>