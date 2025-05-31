<?php
// app/Http/Livewire/Seller/ManageGames.php

namespace App\Http\Livewire\Seller;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Game;
use App\Models\GameStatus;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ManageGames extends Component
{
    use WithPagination, WithFileUploads;

    // Filters
    public $search = '';
    public $statusFilter = '';
    public $typeFilter = '';
    public $conditionFilter = '';

    // Stats
    public $totalGames = 0;
    public $availableGames = 0;
    public $rentedGames = 0;
    public $soldGames = 0;

    // Edit Modal
    public $showEditModal = false;
    public $editingGame = null;
    public $editForm = [
        'name' => '',
        'is_for_rent' => false,
        'rent_price' => null,
        'sell_price' => null,
        'condition' => 'New',
        'description' => '',
        'image' => null
    ];
    public $newImage = null;

    // Delete Confirmation
    public $showDeleteModal = false;
    public $gameToDelete = null;

    // Quick Status Update
    public $showStatusModal = false;
    public $gameForStatus = null;
    public $newStatus = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'typeFilter' => ['except' => ''],
        'conditionFilter' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    protected $rules = [
        'editForm.name' => 'required|string|min:2|max:255',
        'editForm.is_for_rent' => 'boolean',
        'editForm.rent_price' => 'nullable|numeric|min:0.01',
        'editForm.sell_price' => 'nullable|numeric|min:0.01',
        'editForm.condition' => 'required|in:New,Like New,Good,Fair,Poor',
        'editForm.description' => 'nullable|string|max:1000',
        'newImage' => 'nullable|image|max:2048'
    ];

    public function mount()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        try {
            $sellerId = Auth::id();
            
            $this->totalGames = Game::where('seller_id', $sellerId)->count();
            
            // Get games with their statuses
            $games = Game::where('seller_id', $sellerId)->get();
            
            $this->availableGames = 0;
            $this->rentedGames = 0;
            $this->soldGames = 0;
            
            foreach ($games as $game) {
                if ($game->isAvailable()) {
                    $this->availableGames++;
                } elseif ($game->isRented()) {
                    $this->rentedGames++;
                } elseif ($game->isSold()) {
                    $this->soldGames++;
                }
            }
            
        } catch (\Exception $e) {
            Log::error('Error loading game stats: ' . $e->getMessage());
            $this->totalGames = 0;
            $this->availableGames = 0;
            $this->rentedGames = 0;
            $this->soldGames = 0;
        }
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedTypeFilter()
    {
        $this->resetPage();
    }

    public function updatedConditionFilter()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->typeFilter = '';
        $this->conditionFilter = '';
        $this->resetPage();
        session()->flash('message', 'Filters cleared successfully!');
    }

    public function getGamesProperty()
    {
        try {
            $query = Game::where('seller_id', Auth::id());

            // Search filter
            if ($this->search) {
                $query->where('name', 'like', '%' . $this->search . '%');
            }

            // Type filter
            if ($this->typeFilter === 'rent') {
                $query->where('is_for_rent', true);
            } elseif ($this->typeFilter === 'sale') {
                $query->where('is_for_rent', false);
            }

            // Condition filter
            if ($this->conditionFilter) {
                $query->where('condition', $this->conditionFilter);
            }

            // Get all games that match basic filters
            $allGames = $query->get();

            // Apply status filter if needed
            if ($this->statusFilter) {
                $filteredGames = $allGames->filter(function ($game) {
                    switch ($this->statusFilter) {
                        case 'available':
                            return $game->isAvailable();
                        case 'rented':
                            return $game->isRented();
                        case 'sold':
                            return $game->isSold();
                        default:
                            return true;
                    }
                });
            } else {
                $filteredGames = $allGames;
            }

            // Sort by created_at desc
            $sortedGames = $filteredGames->sortByDesc('created_at');

            // Manual pagination
            $currentPage = request()->get('page', 1);
            $perPage = 12;
            $total = $sortedGames->count();
            $offset = ($currentPage - 1) * $perPage;
            $items = $sortedGames->slice($offset, $perPage)->values();

            // Create paginator
            $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
                $items,
                $total,
                $perPage,
                $currentPage,
                [
                    'path' => request()->url(),
                    'pageName' => 'page',
                ]
            );

            return $paginator;

        } catch (\Exception $e) {
            Log::error('Error fetching games: ' . $e->getMessage());
            return new \Illuminate\Pagination\LengthAwarePaginator(
                collect([]), 0, 12, 1, ['path' => request()->url()]
            );
        }
    }

    public function editGame($gameId)
    {
        try {
            $game = Game::where('_id', $gameId)
                       ->where('seller_id', Auth::id())
                       ->first();

            if (!$game) {
                session()->flash('error', 'Game not found.');
                return;
            }

            $this->editingGame = $game;
            $this->editForm = [
                'name' => $game->name,
                'is_for_rent' => $game->is_for_rent,
                'rent_price' => $game->rent_price,
                'sell_price' => $game->sell_price,
                'condition' => $game->condition,
                'description' => $game->description ?? '',
                'image' => $game->image
            ];
            $this->newImage = null;
            $this->showEditModal = true;

        } catch (\Exception $e) {
            Log::error('Error loading game for edit: ' . $e->getMessage());
            session()->flash('error', 'Failed to load game details.');
        }
    }

    public function updateGame()
    {
        $this->validate();

        try {
            if (!$this->editingGame) {
                session()->flash('error', 'No game selected for editing.');
                return;
            }

            // Handle image upload
            $imagePath = $this->editForm['image'];
            if ($this->newImage) {
                // Delete old image if exists
                if ($this->editingGame->image && Storage::exists('public/' . $this->editingGame->image)) {
                    Storage::delete('public/' . $this->editingGame->image);
                }

                $imagePath = $this->newImage->store('games', 'public');
            }

            // Update game
            $this->editingGame->update([
                'name' => $this->editForm['name'],
                'is_for_rent' => $this->editForm['is_for_rent'],
                'rent_price' => $this->editForm['is_for_rent'] ? $this->editForm['rent_price'] : null,
                'sell_price' => !$this->editForm['is_for_rent'] ? $this->editForm['sell_price'] : null,
                'condition' => $this->editForm['condition'],
                'description' => $this->editForm['description'],
                'image' => $imagePath
            ]);

            $this->showEditModal = false;
            $this->editingGame = null;
            $this->newImage = null;
            $this->loadStats();

            session()->flash('success', 'Game updated successfully!');

        } catch (\Exception $e) {
            Log::error('Error updating game: ' . $e->getMessage());
            session()->flash('error', 'Failed to update game: ' . $e->getMessage());
        }
    }

    public function confirmDelete($gameId)
    {
        try {
            // Convert to string to ensure proper comparison with MongoDB ObjectId
            $gameId = (string) $gameId;
            
            $game = Game::where('seller_id', Auth::id())
                       ->where('_id', $gameId)
                       ->first();

            if (!$game) {
                session()->flash('error', 'Game not found.');
                return;
            }

            $this->gameToDelete = $game;
            $this->showDeleteModal = true;

        } catch (\Exception $e) {
            Log::error('Error in confirmDelete: ' . $e->getMessage());
            session()->flash('error', 'Failed to load game details.');
        }
    }

    public function deleteGame()
    {
        try {
            if (!$this->gameToDelete) {
                session()->flash('error', 'No game selected for deletion.');
                return;
            }

            $gameId = $this->gameToDelete->_id;
            $gameName = $this->gameToDelete->name;

            // Delete image if exists
            if ($this->gameToDelete->image && Storage::exists('public/' . $this->gameToDelete->image)) {
                Storage::delete('public/' . $this->gameToDelete->image);
            }

            // Delete game status first (using string conversion for MongoDB)
            GameStatus::where('game_id', (string) $gameId)->delete();

            // Delete the game
            $deleted = Game::where('_id', $gameId)->delete();

            if ($deleted) {
                // Reset modal state
                $this->showDeleteModal = false;
                $this->gameToDelete = null;
                
                // Reload stats
                $this->loadStats();
                
                // Reset page to 1 if we're on a page that might now be empty
                $this->resetPage();

                session()->flash('success', "Game '{$gameName}' has been deleted successfully!");
            } else {
                session()->flash('error', 'Failed to delete game.');
            }

        } catch (\Exception $e) {
            Log::error('Error deleting game: ' . $e->getMessage());
            session()->flash('error', 'Failed to delete game: ' . $e->getMessage());
        }
    }

    // Direct delete method for testing
    public function directDeleteGame($gameId)
    {
        try {
            $gameId = (string) $gameId;
            
            $game = Game::where('seller_id', Auth::id())
                       ->where('_id', $gameId)
                       ->first();
            
            if (!$game) {
                session()->flash('error', 'Game not found');
                return;
            }
            
            $gameName = $game->name;
            
            // Delete image
            if ($game->image && Storage::exists('public/' . $game->image)) {
                Storage::delete('public/' . $game->image);
            }
            
            // Delete status
            GameStatus::where('game_id', $gameId)->delete();
            
            // Delete game
            $deleted = $game->delete();
            
            if ($deleted) {
                $this->loadStats();
                $this->resetPage();
                session()->flash('success', "Game '{$gameName}' deleted successfully!");
            } else {
                session()->flash('error', 'Failed to delete game');  
            }
            
        } catch (\Exception $e) {
            Log::error('Direct delete error: ' . $e->getMessage());
            session()->flash('error', 'Delete failed: ' . $e->getMessage());
        }
    }

    public function openStatusModal($gameId)
    {
        try {
            $game = Game::where('_id', $gameId)
                       ->where('seller_id', Auth::id())
                       ->first();

            if (!$game) {
                session()->flash('error', 'Game not found.');
                return;
            }

            $this->gameForStatus = $game;
            $this->newStatus = $game->isAvailable() ? 'available' : 
                              ($game->isRented() ? 'rented' : 'sold');
            $this->showStatusModal = true;

        } catch (\Exception $e) {
            Log::error('Error opening status modal: ' . $e->getMessage());
            session()->flash('error', 'Failed to load game status.');
        }
    }

    public function updateGameStatus()
    {
        try {
            if (!$this->gameForStatus) {
                session()->flash('error', 'No game selected.');
                return;
            }

            // Check if trying to make sold game available again
            if ($this->gameForStatus->isSold() && $this->newStatus === 'available') {
                session()->flash('error', 'Cannot make sold games available again. Please create a new listing.');
                $this->showStatusModal = false;
                return;
            }

            GameStatus::updateOrCreate(
                ['game_id' => $this->gameForStatus->_id],
                ['status' => $this->newStatus]
            );

            $this->showStatusModal = false;
            $this->gameForStatus = null;
            $this->loadStats();

            session()->flash('success', 'Game status updated successfully!');

        } catch (\Exception $e) {
            Log::error('Error updating game status: ' . $e->getMessage());
            session()->flash('error', 'Failed to update game status: ' . $e->getMessage());
        }
    }

    public function closeModals()
    {
        $this->showEditModal = false;
        $this->showDeleteModal = false;
        $this->showStatusModal = false;
        $this->editingGame = null;
        $this->gameToDelete = null;
        $this->gameForStatus = null;
        $this->newImage = null;
    }

    public function render()
    {
        return view('livewire.seller.manage-games', [
            'games' => $this->games,
        ]);
    }
}