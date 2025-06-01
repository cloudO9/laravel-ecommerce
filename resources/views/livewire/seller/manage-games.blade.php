<div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 p-6">
    <div class="max-w-7xl mx-auto space-y-8">
        
        <!-- Page Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-emerald-500 to-cyan-500 rounded-2xl mb-4 shadow-xl shadow-emerald-500/30">
                <span class="text-2xl">🎮</span>
            </div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-emerald-400 to-cyan-400 bg-clip-text text-transparent mb-2">
                Manage Your Games
            </h1>
            <p class="text-slate-400">Organize and track your game listings</p>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-slate-800/60 backdrop-blur-lg border border-slate-700/50 rounded-2xl p-6 text-center hover:border-blue-500/50 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <span class="text-xl">🎮</span>
                </div>
                <div class="text-3xl font-bold text-blue-400 mb-2">{{ $totalGames }}</div>
                <div class="text-slate-300 font-medium">Total Games</div>
            </div>
            
            <div class="bg-slate-800/60 backdrop-blur-lg border border-slate-700/50 rounded-2xl p-6 text-center hover:border-green-500/50 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <span class="text-xl">✅</span>
                </div>
                <div class="text-3xl font-bold text-green-400 mb-2">{{ $availableGames }}</div>
                <div class="text-slate-300 font-medium">Available</div>
            </div>
            
            <div class="bg-slate-800/60 backdrop-blur-lg border border-slate-700/50 rounded-2xl p-6 text-center hover:border-yellow-500/50 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <span class="text-xl">📅</span>
                </div>
                <div class="text-3xl font-bold text-yellow-400 mb-2">{{ $rentedGames }}</div>
                <div class="text-slate-300 font-medium">Rented</div>
            </div>

            <div class="bg-slate-800/60 backdrop-blur-lg border border-slate-700/50 rounded-2xl p-6 text-center hover:border-red-500/50 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-500 rounded-xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <span class="text-xl">❌</span>
                </div>
                <div class="text-3xl font-bold text-red-400 mb-2">{{ $soldGames }}</div>
                <div class="text-slate-300 font-medium">Sold</div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="bg-slate-800/60 backdrop-blur-lg border border-slate-700/50 rounded-2xl p-8 mb-8 shadow-lg">
            <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-3">
                <span class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg flex items-center justify-center">🔍</span>
                Filter Games
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <!-- Search -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-300 mb-2">Search</label>
                    <div class="relative">
                        <input type="text" wire:model.live="search" placeholder="Game name..."
                            class="w-full pl-10 pr-4 py-3 bg-slate-900/60 border border-slate-600/50 rounded-xl text-white placeholder-slate-400 
                                   focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500/40 transition-all duration-300">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Status</label>
                    <select wire:model.live="statusFilter" 
                        class="w-full px-4 py-3 bg-slate-900/60 border border-slate-600/50 rounded-xl text-white 
                               focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500/40 transition-all duration-300">
                        <option value="">All Status</option>
                        <option value="available">✅ Available</option>
                        <option value="rented">📅 Rented</option>
                        <option value="sold">❌ Sold</option>
                    </select>
                </div>

                <!-- Type Filter -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Type</label>
                    <select wire:model.live="typeFilter" 
                        class="w-full px-4 py-3 bg-slate-900/60 border border-slate-600/50 rounded-xl text-white 
                               focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500/40 transition-all duration-300">
                        <option value="">All Types</option>
                        <option value="sale">💰 For Sale</option>
                        <option value="rent">📅 For Rent</option>
                    </select>
                </div>

                <!-- Condition Filter -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Condition</label>
                    <select wire:model.live="conditionFilter" 
                        class="w-full px-4 py-3 bg-slate-900/60 border border-slate-600/50 rounded-xl text-white 
                               focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500/40 transition-all duration-300">
                        <option value="">All Conditions</option>
                        <option value="New">🆕 New</option>
                        <option value="Like New">✨ Like New</option>
                        <option value="Good">👍 Good</option>
                        <option value="Fair">👌 Fair</option>
                        <option value="Poor">👎 Poor</option>
                    </select>
                </div>

                <!-- Clear Filters -->
                <div class="flex items-end">
                    <button wire:click="clearFilters" 
                        class="w-full bg-slate-700/70 hover:bg-slate-600/80 text-slate-200 hover:text-white px-4 py-3 rounded-xl font-medium transition-all duration-300 flex items-center justify-center gap-2">
                        <span>🗑️</span>
                        Clear Filters
                    </button>
                </div>
            </div>
        </div>

        <!-- Games Grid -->
        <div class="space-y-6">
            @if($games->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($games as $game)
                        <div class="bg-slate-800/60 backdrop-blur-lg border border-slate-700/50 rounded-2xl overflow-hidden hover:border-emerald-500/50 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1" wire:key="game-{{ $game->_id }}">
                            <!-- Game Image -->
                            <div class="relative aspect-square bg-slate-700/30 overflow-hidden">
                                @if($game->image)
                                    <img src="{{ Storage::url($game->image) }}" alt="{{ $game->name }}" 
                                        class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <span class="text-6xl opacity-50">🎮</span>
                                    </div>
                                @endif
                                
                                <!-- Status Badge -->
                                <div class="absolute top-3 left-3">
                                    <span class="px-3 py-1.5 rounded-full text-xs font-semibold border {{ $game->getStatusColor() }} bg-slate-900/80 backdrop-blur-sm shadow-lg">
                                        {{ $game->getStatusDisplay() }}
                                    </span>
                                </div>

                                <!-- Type Badge -->
                                <div class="absolute top-3 right-3">
                                    <span class="px-3 py-1.5 rounded-full text-xs font-semibold border shadow-lg bg-slate-900/80 backdrop-blur-sm
                                        {{ $game->is_for_rent ? 'text-purple-300 border-purple-500/40' : 'text-green-300 border-green-500/40' }}">
                                        {{ $game->is_for_rent ? '📅 Rent' : '💰 Sale' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Game Info -->
                            <div class="p-6">
                                <h3 class="font-bold text-white text-lg mb-4 truncate">{{ $game->name }}</h3>
                                
                                <div class="space-y-3 mb-6">
                                    <div class="flex justify-between items-center">
                                        <span class="text-slate-400 text-sm font-medium">Price:</span>
                                        <span class="text-emerald-400 font-bold text-lg">{{ $game->getFormattedPrice() }}</span>
                                    </div>
                                    
                                    <div class="flex justify-between items-center">
                                        <span class="text-slate-400 text-sm font-medium">Condition:</span>
                                        <span class="text-slate-200 text-sm font-medium">{{ $game->condition }}</span>
                                    </div>
                                    
                                    <div class="flex justify-between items-center">
                                        <span class="text-slate-400 text-sm font-medium">Posted:</span>
                                        <span class="text-slate-200 text-sm">{{ $game->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex gap-2">
                                    <!-- Edit Button -->
                                    <button wire:click="editGame('{{ $game->_id }}')" 
                                        class="flex-1 bg-blue-600/80 hover:bg-blue-600 text-white px-3 py-2.5 rounded-lg text-sm font-semibold transition-all duration-300 flex items-center justify-center gap-2 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                        <span>✏️</span>
                                        Edit
                                    </button>

                                    <!-- Status Button -->
                                    <button wire:click="openStatusModal('{{ $game->_id }}')" 
                                        class="flex-1 bg-purple-600/80 hover:bg-purple-600 text-white px-3 py-2.5 rounded-lg text-sm font-semibold transition-all duration-300 flex items-center justify-center gap-2 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                        <span>🔄</span>
                                        Status
                                    </button>                                

                                    <!-- Delete Button -->
                                    <button wire:click="confirmDelete('{{ $game->_id }}')" 
                                        class="bg-red-600/80 hover:bg-red-600 text-white px-3 py-2.5 rounded-lg text-sm font-semibold transition-all duration-300 flex items-center justify-center shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                        <span>🗑️</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($games->hasPages())
                    <div class="flex justify-center mt-8">
                        {{ $games->links() }}
                    </div>
                @endif
            @else
                <!-- No Games -->
                <div class="text-center py-20">
                    <div class="w-32 h-32 bg-slate-800/60 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <span class="text-6xl opacity-50">🎮</span>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-200 mb-4">No games found</h3>
                    <p class="text-slate-400 mb-6 max-w-md mx-auto">
                        @if($search || $statusFilter || $typeFilter || $conditionFilter)
                            No games match your current filters. Try adjusting your search criteria.
                        @else
                            You haven't posted any games yet. Start by adding your first game listing!
                        @endif
                    </p>
                    @if($search || $statusFilter || $typeFilter || $conditionFilter)
                        <button wire:click="clearFilters" 
                            class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 flex items-center gap-3 mx-auto shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            <span>🔄</span>
                            Clear Filters
                        </button>
                    @endif
                </div>
            @endif
        </div>

        <!-- Edit Game Modal -->
        @if($showEditModal)
            <div class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center p-4 z-50">
                <div class="bg-slate-800/90 backdrop-blur-lg rounded-2xl border border-slate-600/50 w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl">
                    <div class="p-8">
                        <div class="flex justify-between items-center mb-8">
                            <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                                <span class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">✏️</span>
                                Edit Game
                            </h2>
                            <button wire:click="closeModals" class="text-slate-400 hover:text-white p-2 rounded-lg hover:bg-slate-700/50 transition-colors">
                                <span class="text-xl">✕</span>
                            </button>
                        </div>

                        <form wire:submit.prevent="updateGame" class="space-y-6">
                            <!-- Game Name -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-200 mb-2">Game Name</label>
                                <input type="text" wire:model="editForm.name" 
                                    class="w-full px-4 py-3 bg-slate-900/60 border border-slate-600/50 rounded-xl text-white 
                                           focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500/40 transition-all duration-300">
                                @error('editForm.name') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Type Toggle -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-200 mb-3">Listing Type</label>
                                <div class="grid grid-cols-2 gap-4">
                                    <label class="flex items-center p-4 bg-slate-900/40 border border-slate-600/30 rounded-xl cursor-pointer hover:border-emerald-500/50 transition-colors">
                                        <input type="radio" wire:model="editForm.is_for_rent" value="0" class="sr-only">
                                        <div class="w-4 h-4 rounded-full border-2 border-slate-600 mr-3 flex items-center justify-center {{ !$editForm['is_for_rent'] ? 'bg-emerald-500 border-emerald-500' : '' }}">
                                            @if(!$editForm['is_for_rent']) <div class="w-2 h-2 bg-white rounded-full"></div> @endif
                                        </div>
                                        <span class="text-slate-300 font-medium">💰 For Sale</span>
                                    </label>
                                    <label class="flex items-center p-4 bg-slate-900/40 border border-slate-600/30 rounded-xl cursor-pointer hover:border-purple-500/50 transition-colors">
                                        <input type="radio" wire:model="editForm.is_for_rent" value="1" class="sr-only">
                                        <div class="w-4 h-4 rounded-full border-2 border-slate-600 mr-3 flex items-center justify-center {{ $editForm['is_for_rent'] ? 'bg-emerald-500 border-emerald-500' : '' }}">
                                            @if($editForm['is_for_rent']) <div class="w-2 h-2 bg-white rounded-full"></div> @endif
                                        </div>
                                        <span class="text-slate-300 font-medium">📅 For Rent</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Price Fields -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @if($editForm['is_for_rent'])
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-200 mb-2">Rent Price (per day)</label>
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-purple-400 font-bold">$</span>
                                            <input type="number" step="0.01" wire:model="editForm.rent_price" 
                                                class="w-full pl-8 pr-4 py-3 bg-slate-900/60 border border-slate-600/50 rounded-xl text-white 
                                                       focus:outline-none focus:ring-2 focus:ring-purple-500/40 focus:border-purple-500/40 transition-all duration-300">
                                        </div>
                                        @error('editForm.rent_price') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                @else
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-200 mb-2">Sale Price</label>
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-emerald-400 font-bold">$</span>
                                            <input type="number" step="0.01" wire:model="editForm.sell_price" 
                                                class="w-full pl-8 pr-4 py-3 bg-slate-900/60 border border-slate-600/50 rounded-xl text-white 
                                                       focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500/40 transition-all duration-300">
                                        </div>
                                        @error('editForm.sell_price') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                @endif
                            </div>

                            <!-- Condition -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-200 mb-2">Condition</label>
                                <select wire:model="editForm.condition" 
                                    class="w-full px-4 py-3 bg-slate-900/60 border border-slate-600/50 rounded-xl text-white 
                                           focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500/40 transition-all duration-300">
                                    <option value="New">🆕 New</option>
                                    <option value="Like New">✨ Like New</option>
                                    <option value="Good">👍 Good</option>
                                    <option value="Fair">👌 Fair</option>
                                    <option value="Poor">👎 Poor</option>
                                </select>
                                @error('editForm.condition') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-200 mb-2">Description (Optional)</label>
                                <textarea wire:model="editForm.description" rows="3" 
                                    class="w-full px-4 py-3 bg-slate-900/60 border border-slate-600/50 rounded-xl text-white 
                                           focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500/40 transition-all duration-300 resize-none"></textarea>
                                @error('editForm.description') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Image Upload -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-200 mb-2">Game Image</label>
                                <input type="file" wire:model="newImage" accept="image/*"
                                    class="w-full px-4 py-3 bg-slate-900/60 border border-slate-600/50 rounded-xl text-white 
                                           focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500/40 transition-all duration-300
                                           file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-emerald-600 file:text-white file:font-medium">
                                @error('newImage') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                                
                                <!-- Current Image Preview -->
                                @if($editForm['image'] && !$newImage)
                                    <div class="mt-3">
                                        <p class="text-slate-400 text-sm mb-2">Current Image:</p>
                                        <img src="{{ Storage::url($editForm['image']) }}" alt="Current game image" 
                                            class="w-20 h-20 object-cover rounded-lg border border-slate-600">
                                    </div>
                                @endif

                                <!-- New Image Preview -->
                                @if($newImage)
                                    <div class="mt-3">
                                        <p class="text-slate-400 text-sm mb-2">New Image Preview:</p>
                                        <img src="{{ $newImage->temporaryUrl() }}" alt="New game image" 
                                            class="w-20 h-20 object-cover rounded-lg border border-slate-600">
                                    </div>
                                @endif
                            </div>

                            <!-- Modal Actions -->
                            <div class="flex gap-4 pt-6 border-t border-slate-700/50">
                                <button type="submit" 
                                    class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                    Update Game
                                </button>
                                <button type="button" wire:click="closeModals" 
                                    class="flex-1 bg-slate-600 hover:bg-slate-500 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 shadow-lg">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        <!-- Delete Confirmation Modal -->
        @if($showDeleteModal)
            <div class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center p-4 z-50">
                <div class="bg-slate-800/90 backdrop-blur-lg rounded-2xl border border-slate-600/50 w-full max-w-md shadow-2xl">
                    <div class="p-8">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-red-600/20 rounded-full flex items-center justify-center mx-auto mb-6">
                                <span class="text-3xl">⚠️</span>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-3">Delete Game</h3>
                            <p class="text-slate-400 mb-8">
                                Are you sure you want to delete "<strong class="text-white">{{ $gameToDelete?->name }}</strong>"? 
                                This action cannot be undone.
                            </p>
                            
                            <div class="flex gap-4">
                                <button wire:click="deleteGame" 
                                    class="flex-1 bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 shadow-lg">
                                    Delete
                                </button>
                                <button wire:click="closeModals" 
                                    class="flex-1 bg-slate-600 hover:bg-slate-500 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 shadow-lg">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Status Update Modal -->
        @if($showStatusModal)
            <div class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center p-4 z-50">
                <div class="bg-slate-800/90 backdrop-blur-lg rounded-2xl border border-slate-600/50 w-full max-w-md shadow-2xl">
                    <div class="p-8">
                        <div class="text-center mb-8">
                            <div class="w-16 h-16 bg-purple-600/20 rounded-full flex items-center justify-center mx-auto mb-6">
                                <span class="text-3xl">🔄</span>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-3">Update Status</h3>
                            <p class="text-slate-400">
                                Change the availability status for "<strong class="text-white">{{ $gameForStatus?->name }}</strong>"
                            </p>
                        </div>

                        <div class="space-y-3 mb-8">
                            <label class="flex items-center p-4 rounded-xl border border-slate-700/50 hover:border-green-500/50 cursor-pointer transition-colors">
                                <input type="radio" wire:model="newStatus" value="available" class="sr-only">
                                <div class="w-4 h-4 rounded-full border-2 border-slate-600 mr-4 flex items-center justify-center {{ $newStatus === 'available' ? 'bg-green-500 border-green-500' : '' }}">
                                    @if($newStatus === 'available') <div class="w-2 h-2 bg-white rounded-full"></div> @endif
                                </div>
                                <span class="text-green-400 font-medium">✅ Available</span>
                            </label>

                            <label class="flex items-center p-4 rounded-xl border border-slate-700/50 hover:border-yellow-500/50 cursor-pointer transition-colors">
                                <input type="radio" wire:model="newStatus" value="rented" class="sr-only">
                                <div class="w-4 h-4 rounded-full border-2 border-slate-600 mr-4 flex items-center justify-center {{ $newStatus === 'rented' ? 'bg-yellow-500 border-yellow-500' : '' }}">
                                    @if($newStatus === 'rented') <div class="w-2 h-2 bg-white rounded-full"></div> @endif
                                </div>
                                <span class="text-yellow-400 font-medium">📅 Rented</span>
                            </label>

                            @if(!$gameForStatus?->isSold())
                                <label class="flex items-center p-4 rounded-xl border border-slate-700/50 hover:border-red-500/50 cursor-pointer transition-colors">
                                    <input type="radio" wire:model="newStatus" value="sold" class="sr-only">
                                    <div class="w-4 h-4 rounded-full border-2 border-slate-600 mr-4 flex items-center justify-center {{ $newStatus === 'sold' ? 'bg-red-500 border-red-500' : '' }}">
                                        @if($newStatus === 'sold') <div class="w-2 h-2 bg-white rounded-full"></div> @endif
                                    </div>
                                    <span class="text-red-400 font-medium">❌ Sold</span>
                                </label>
                            @endif
                        </div>

                        <div class="flex gap-4">
                            <button wire:click="updateGameStatus" 
                                class="flex-1 bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 shadow-lg">
                                Update Status
                            </button>
                            <button wire:click="closeModals" 
                                class="flex-1 bg-slate-600 hover:bg-slate-500 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 shadow-lg">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Loading State -->
        <div wire:loading class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-40">
            <div class="bg-slate-800/90 backdrop-blur-lg rounded-2xl p-8 border border-slate-600/50 shadow-2xl">
                <div class="flex items-center gap-4">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-emerald-500"></div>
                    <span class="text-white font-semibold text-lg">Processing...</span>
                </div>
            </div>
        </div>
    </div>
</div>