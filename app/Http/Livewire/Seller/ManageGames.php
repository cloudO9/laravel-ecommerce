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

            if ($this->search) {
                $query->where('name', 'like', '%' . $this->search . '%');
            }

            if ($this->typeFilter === 'rent') {
                $query->where('is_for_rent', true);
            } elseif ($this->typeFilter === 'sale') {
                $query->where('is_for_rent', false);
            }

            if ($this->conditionFilter) {
                $query->where('condition', $this->conditionFilter);
            }

            $allGames = $query->get();

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

            $sortedGames = $filteredGames->sortByDesc('created_at');

            $currentPage = request()->get('page', 1);
            $perPage = 12;
            $total = $sortedGames->count();
            $offset = ($currentPage - 1) * $perPage;
            $items = $sortedGames->slice($offset, $perPage)->values();

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

            $imagePath = $this->editForm['image'];
            if ($this->newImage) {
                if ($this->editingGame->image && Storage::exists('public/' . $this->editingGame->image)) {
                    Storage::delete('public/' . $this->editingGame->image);
                }

                $imagePath = $this->newImage->store('games', 'public');
            }

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

            if ($this->gameToDelete->image && Storage::exists('public/' . $this->gameToDelete->image)) {
                Storage::delete('public/' . $this->gameToDelete->image);
            }

            GameStatus::where('game_id', (string) $gameId)->delete();

            $deleted = Game::where('_id', $gameId)->delete();

            if ($deleted) {
                $this->showDeleteModal = false;
                $this->gameToDelete = null;
                $this->loadStats();
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