<div>
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-slate-900/40 rounded-xl p-4 text-center border border-slate-700/30">
            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-lg flex items-center justify-center mx-auto mb-2">
                <span class="text-sm">🎮</span>
            </div>
            <div class="text-xl font-bold text-blue-400">{{ $totalGames }}</div>
            <div class="text-slate-400 text-xs">Total Games</div>
        </div>
        
        <div class="bg-slate-900/40 rounded-xl p-4 text-center border border-slate-700/30">
            <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-emerald-500 rounded-lg flex items-center justify-center mx-auto mb-2">
                <span class="text-sm">✅</span>
            </div>
            <div class="text-xl font-bold text-green-400">{{ $availableGames }}</div>
            <div class="text-slate-400 text-xs">Available</div>
        </div>
        
        <div class="bg-slate-900/40 rounded-xl p-4 text-center border border-slate-700/30">
            <div class="w-8 h-8 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-lg flex items-center justify-center mx-auto mb-2">
                <span class="text-sm">📅</span>
            </div>
            <div class="text-xl font-bold text-yellow-400">{{ $rentedGames }}</div>
            <div class="text-slate-400 text-xs">Rented</div>
        </div>

        <div class="bg-slate-900/40 rounded-xl p-4 text-center border border-slate-700/30">
            <div class="w-8 h-8 bg-gradient-to-r from-red-500 to-pink-500 rounded-lg flex items-center justify-center mx-auto mb-2">
                <span class="text-sm">❌</span>
            </div>
            <div class="text-xl font-bold text-red-400">{{ $soldGames }}</div>
            <div class="text-slate-400 text-xs">Sold</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-slate-900/40 rounded-xl p-4 mb-6 border border-slate-700/30">
        <h3 class="text-white font-medium mb-3 flex items-center gap-2">
            <span class="text-lg">🔍</span>
            Filter Games
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-6 gap-3">
            <!-- Search -->
            <div>
                <label class="block text-xs font-medium text-slate-300 mb-1">Search</label>
                <input type="text" wire:model.live="search" placeholder="Game name..."
                    class="w-full px-3 py-2 bg-slate-800 border border-slate-600/50 rounded text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/40">
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-xs font-medium text-slate-300 mb-1">Status</label>
                <select wire:model.live="statusFilter" 
                    class="w-full px-3 py-2 bg-slate-800 border border-slate-600/50 rounded text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/40">
                    <option value="">All Status</option>
                    <option value="available">✅ Available</option>
                    <option value="rented">📅 Rented</option>
                    <option value="sold">❌ Sold</option>
                </select>
            </div>

            <!-- Type Filter -->
            <div>
                <label class="block text-xs font-medium text-slate-300 mb-1">Type</label>
                <select wire:model.live="typeFilter" 
                    class="w-full px-3 py-2 bg-slate-800 border border-slate-600/50 rounded text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/40">
                    <option value="">All Types</option>
                    <option value="sale">💰 For Sale</option>
                    <option value="rent">📅 For Rent</option>
                </select>
            </div>

            <!-- Condition Filter -->
            <div>
                <label class="block text-xs font-medium text-slate-300 mb-1">Condition</label>
                <select wire:model.live="conditionFilter" 
                    class="w-full px-3 py-2 bg-slate-800 border border-slate-600/50 rounded text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/40">
                    <option value="">All Conditions</option>
                    <option value="New">🆕 New</option>
                    <option value="Like New">✨ Like New</option>
                    <option value="Good">👍 Good</option>
                    <option value="Fair">👌 Fair</option>
                    <option value="Poor">👎 Poor</option>
                </select>
            </div>

            <!-- Clear Filters -->
            <div class="flex items-end md:col-span-2">
                <button wire:click="clearFilters" 
                    class="w-full bg-slate-700 hover:bg-slate-600 text-slate-200 px-3 py-2 rounded text-sm transition-colors flex items-center justify-center gap-1">
                    <span>🗑️</span>
                    Clear Filters
                </button>
            </div>
        </div>
    </div>

    <!-- Games Grid -->
    <div class="space-y-4">
        @if($games->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($games as $game)
                    <div class="bg-slate-900/40 rounded-xl border border-slate-700/30 hover:border-emerald-500/30 transition-colors overflow-hidden" wire:key="game-{{ $game->_id }}">
                        <!-- Game Image -->
                        <div class="relative aspect-square bg-slate-800/50 overflow-hidden">
                            @if($game->image)
                                <img src="{{ Storage::url($game->image) }}" alt="{{ $game->name }}" 
                                    class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <span class="text-6xl opacity-50">🎮</span>
                                </div>
                            @endif
                            
                            <!-- Status Badge -->
                            <div class="absolute top-2 left-2">
                                <span class="px-2 py-1 rounded-full text-xs font-medium border {{ $game->getStatusColor() }} bg-slate-900/80 backdrop-blur-sm">
                                    {{ $game->getStatusDisplay() }}
                                </span>
                            </div>

                            <!-- Type Badge -->
                            <div class="absolute top-2 right-2">
                                <span class="px-2 py-1 rounded-full text-xs font-medium border 
                                    {{ $game->is_for_rent ? 'bg-purple-600/20 text-purple-300 border-purple-500/40' : 'bg-green-600/20 text-green-300 border-green-500/40' }}">
                                    {{ $game->is_for_rent ? '📅 Rent' : '💰 Sale' }}
                                </span>
                            </div>
                        </div>

                        <!-- Game Info -->
                        <div class="p-4">
                            <h3 class="font-semibold text-white text-lg mb-2 truncate">{{ $game->name }}</h3>
                            
                            <div class="space-y-2 mb-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-slate-400 text-sm">Price:</span>
                                    <span class="text-emerald-400 font-semibold">{{ $game->getFormattedPrice() }}</span>
                                </div>
                                
                                <div class="flex justify-between items-center">
                                    <span class="text-slate-400 text-sm">Condition:</span>
                                    <span class="text-slate-300 text-sm">{{ $game->condition }}</span>
                                </div>
                                
                                <div class="flex justify-between items-center">
                                    <span class="text-slate-400 text-sm">Posted:</span>
                                    <span class="text-slate-300 text-sm">{{ $game->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-2">
                                <!-- Edit Button -->
                                <button wire:click="editGame('{{ $game->_id }}')" 
                                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-sm font-medium transition-colors flex items-center justify-center gap-1">
                                    <span>✏️</span>
                                    Edit
                                </button>

                                <!-- Status Button -->
                                <button wire:click="openStatusModal('{{ $game->_id }}')" 
                                    class="flex-1 bg-purple-600 hover:bg-purple-700 text-white px-3 py-2 rounded text-sm font-medium transition-colors flex items-center justify-center gap-1">
                                    <span>🔄</span>
                                    Status
                                </button>                                

                                <!-- Normal Delete Button -->
                                <button wire:click="confirmDelete('{{ $game->_id }}')" 
                                    class="bg-red-600 hover:bg-red-700 text-white px-2 py-2 rounded text-sm font-medium transition-colors flex items-center justify-center">
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
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-slate-700/30 rounded-3xl flex items-center justify-center mx-auto mb-4">
                    <span class="text-4xl opacity-50">🎮</span>
                </div>
                <h3 class="text-xl font-bold text-slate-300 mb-2">No games found</h3>
                <p class="text-slate-400 text-sm mb-4">
                    @if($search || $statusFilter || $typeFilter || $conditionFilter)
                        No games match your current filters. Try adjusting your search criteria.
                    @else
                        You haven't posted any games yet. Start by adding your first game listing!
                    @endif
                </p>
                @if($search || $statusFilter || $typeFilter || $conditionFilter)
                    <button wire:click="clearFilters" 
                        class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2 mx-auto">
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
            <div class="bg-slate-800 rounded-xl border border-slate-600 w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl">
                <div class="p-6 bg-slate-800">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-white">Edit Game</h2>
                        <button wire:click="closeModals" class="text-slate-400 hover:text-white">
                            <span class="text-xl">✕</span>
                        </button>
                    </div>

                    <form wire:submit.prevent="updateGame" class="space-y-4">
                        <!-- Game Name -->
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-1">Game Name</label>
                            <input type="text" wire:model="editForm.name" 
                                class="w-full px-3 py-2 bg-slate-800 border border-slate-600 rounded text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            @error('editForm.name') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Type Toggle -->
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Listing Type</label>
                            <div class="flex gap-4">
                                <label class="flex items-center">
                                    <input type="radio" wire:model="editForm.is_for_rent" value="0" class="sr-only">
                                    <div class="w-4 h-4 rounded-full border-2 border-slate-600 mr-2 flex items-center justify-center {{ !$editForm['is_for_rent'] ? 'bg-emerald-500 border-emerald-500' : '' }}">
                                        @if(!$editForm['is_for_rent']) <div class="w-2 h-2 bg-white rounded-full"></div> @endif
                                    </div>
                                    <span class="text-slate-300">💰 For Sale</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" wire:model="editForm.is_for_rent" value="1" class="sr-only">
                                    <div class="w-4 h-4 rounded-full border-2 border-slate-600 mr-2 flex items-center justify-center {{ $editForm['is_for_rent'] ? 'bg-emerald-500 border-emerald-500' : '' }}">
                                        @if($editForm['is_for_rent']) <div class="w-2 h-2 bg-white rounded-full"></div> @endif
                                    </div>
                                    <span class="text-slate-300">📅 For Rent</span>
                                </label>
                            </div>
                        </div>

                        <!-- Price Fields -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($editForm['is_for_rent'])
                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-1">Rent Price (per day)</label>
                                    <input type="number" step="0.01" wire:model="editForm.rent_price" 
                                        class="w-full px-3 py-2 bg-slate-800 border border-slate-600 rounded text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                    @error('editForm.rent_price') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                                </div>
                            @else
                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-1">Sale Price</label>
                                    <input type="number" step="0.01" wire:model="editForm.sell_price" 
                                        class="w-full px-3 py-2 bg-slate-800 border border-slate-600 rounded text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                    @error('editForm.sell_price') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                                </div>
                            @endif
                        </div>

                        <!-- Condition -->
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-1">Condition</label>
                            <select wire:model="editForm.condition" 
                                class="w-full px-3 py-2 bg-slate-800 border border-slate-600 rounded text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                <option value="New">🆕 New</option>
                                <option value="Like New">✨ Like New</option>
                                <option value="Good">👍 Good</option>
                                <option value="Fair">👌 Fair</option>
                                <option value="Poor">👎 Poor</option>
                            </select>
                            @error('editForm.condition') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-1">Description (Optional)</label>
                            <textarea wire:model="editForm.description" rows="3" 
                                class="w-full px-3 py-2 bg-slate-800 border border-slate-600 rounded text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 resize-none"></textarea>
                            @error('editForm.description') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Image Upload -->
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-1">Game Image</label>
                            <input type="file" wire:model="newImage" accept="image/*"
                                class="w-full px-3 py-2 bg-slate-800 border border-slate-600 rounded text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            @error('newImage') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                            
                            <!-- Current Image Preview -->
                            @if($editForm['image'] && !$newImage)
                                <div class="mt-2">
                                    <p class="text-slate-400 text-sm mb-1">Current Image:</p>
                                    <img src="{{ Storage::url($editForm['image']) }}" alt="Current game image" 
                                        class="w-20 h-20 object-cover rounded border border-slate-600">
                                </div>
                            @endif

                            <!-- New Image Preview -->
                            @if($newImage)
                                <div class="mt-2">
                                    <p class="text-slate-400 text-sm mb-1">New Image Preview:</p>
                                    <img src="{{ $newImage->temporaryUrl() }}" alt="New game image" 
                                        class="w-20 h-20 object-cover rounded border border-slate-600">
                                </div>
                            @endif
                        </div>

                        <!-- Modal Actions -->
                        <div class="flex gap-3 pt-4 border-t border-slate-600 mt-6">
                            <button type="submit" 
                                class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-3 rounded-lg font-medium transition-colors shadow-lg">
                                Update Game
                            </button>
                            <button type="button" wire:click="closeModals" 
                                class="flex-1 bg-slate-600 hover:bg-slate-500 text-white px-4 py-3 rounded-lg font-medium transition-colors shadow-lg">
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
            <div class="bg-slate-800 rounded-xl border border-slate-600 w-full max-w-md shadow-2xl">
                <div class="p-6 bg-slate-800">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-red-600/20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl">⚠️</span>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">Delete Game</h3>
                        <p class="text-slate-400 mb-6">
                            Are you sure you want to delete "<strong class="text-white">{{ $gameToDelete?->name }}</strong>"? 
                            This action cannot be undone.
                        </p>
                        
                        <div class="flex gap-3">
                            <button wire:click="deleteGame" 
                                class="flex-1 bg-red-600 hover:bg-red-700 text-white px-4 py-3 rounded-lg font-medium transition-colors shadow-lg">
                                Delete
                            </button>
                            <button wire:click="closeModals" 
                                class="flex-1 bg-slate-600 hover:bg-slate-500 text-white px-4 py-3 rounded-lg font-medium transition-colors shadow-lg">
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
            <div class="bg-slate-800 rounded-xl border border-slate-600 w-full max-w-md shadow-2xl">
                <div class="p-6 bg-slate-800">
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-purple-600/20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl">🔄</span>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">Update Status</h3>
                        <p class="text-slate-400">
                            Change the availability status for "<strong class="text-white">{{ $gameForStatus?->name }}</strong>"
                        </p>
                    </div>

                    <div class="space-y-3 mb-6">
                        <label class="flex items-center p-3 rounded-lg border border-slate-700 hover:border-green-500/50 cursor-pointer">
                            <input type="radio" wire:model="newStatus" value="available" class="sr-only">
                            <div class="w-4 h-4 rounded-full border-2 border-slate-600 mr-3 flex items-center justify-center {{ $newStatus === 'available' ? 'bg-green-500 border-green-500' : '' }}">
                                @if($newStatus === 'available') <div class="w-2 h-2 bg-white rounded-full"></div> @endif
                            </div>
                            <span class="text-green-400">✅ Available</span>
                        </label>

                        <label class="flex items-center p-3 rounded-lg border border-slate-700 hover:border-yellow-500/50 cursor-pointer">
                            <input type="radio" wire:model="newStatus" value="rented" class="sr-only">
                            <div class="w-4 h-4 rounded-full border-2 border-slate-600 mr-3 flex items-center justify-center {{ $newStatus === 'rented' ? 'bg-yellow-500 border-yellow-500' : '' }}">
                                @if($newStatus === 'rented') <div class="w-2 h-2 bg-white rounded-full"></div> @endif
                            </div>
                            <span class="text-yellow-400">📅 Rented</span>
                        </label>

                        @if(!$gameForStatus?->isSold())
                            <label class="flex items-center p-3 rounded-lg border border-slate-700 hover:border-red-500/50 cursor-pointer">
                                <input type="radio" wire:model="newStatus" value="sold" class="sr-only">
                                <div class="w-4 h-4 rounded-full border-2 border-slate-600 mr-3 flex items-center justify-center {{ $newStatus === 'sold' ? 'bg-red-500 border-red-500' : '' }}">
                                    @if($newStatus === 'sold') <div class="w-2 h-2 bg-white rounded-full"></div> @endif
                                </div>
                                <span class="text-red-400">❌ Sold</span>
                            </label>
                        @endif
                    </div>

                    <div class="flex gap-3">
                        <button wire:click="updateGameStatus" 
                            class="flex-1 bg-purple-600 hover:bg-purple-700 text-white px-4 py-3 rounded-lg font-medium transition-colors shadow-lg">
                            Update Status
                        </button>
                        <button wire:click="closeModals" 
                            class="flex-1 bg-slate-600 hover:bg-slate-500 text-white px-4 py-3 rounded-lg font-medium transition-colors shadow-lg">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Loading State -->
    <div wire:loading class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-40">
        <div class="bg-slate-800 rounded-xl p-6 border border-slate-600 shadow-2xl">
            <div class="flex items-center gap-3">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-emerald-500"></div>
                <span class="text-white font-medium">Processing...</span>
            </div>
        </div>
    </div>
</div>