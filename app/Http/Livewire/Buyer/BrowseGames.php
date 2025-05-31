<?php

namespace App\Http\Livewire\Buyer;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Game;
use App\Models\User;

class BrowseGames extends Component
{
    use WithPagination;

    // Search and filter properties
    public $search = '';
    public $conditionFilter = '';
    public $typeFilter = '';
    public $minPrice = '';
    public $maxPrice = '';
    public $sortBy = 'newest';

    // Listeners for Livewire events
    protected $listeners = ['gameViewed' => 'refreshGames'];

    // Reset pagination when filters change
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingConditionFilter()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function updatingMinPrice()
    {
        $this->resetPage();
    }

    public function updatingMaxPrice()
    {
        $this->resetPage();
    }

    public function updatingSortBy()
    {
        $this->resetPage();
    }

    // Clear all filters
    public function clearFilters()
    {
        $this->search = '';
        $this->conditionFilter = '';
        $this->typeFilter = '';
        $this->minPrice = '';
        $this->maxPrice = '';
        $this->sortBy = 'newest';
        $this->resetPage();
        
        session()->flash('message', 'Filters cleared successfully!');
    }

    // View specific game - redirect to detail page
    public function viewGame($gameId)
    {
        return redirect()->route('buyer.game-detail', ['gameId' => $gameId]);
    }

    // Refresh games list
    public function refreshGames()
    {
        // This method can be called to refresh the games list
    }

    public function render()
    {
        // Start building the query
        $query = Game::query();

        // Search by name (case-insensitive)
        if (!empty($this->search)) {
            $query->where('name', 'regex', new \MongoDB\BSON\Regex($this->search, 'i'));
        }

        // Filter by condition
        if (!empty($this->conditionFilter)) {
            $query->where('condition', $this->conditionFilter);
        }

        // Filter by type (sale/rent)
        if (!empty($this->typeFilter)) {
            if ($this->typeFilter === 'sale') {
                $query->where('is_for_rent', false);
            } elseif ($this->typeFilter === 'rent') {
                $query->where('is_for_rent', true);
            }
        }

        // Filter by price range
        if (!empty($this->minPrice)) {
            $query->where(function ($q) {
                $q->where(function ($subQ) {
                    $subQ->where('is_for_rent', false)
                         ->where('sell_price', '>=', (float) $this->minPrice);
                })->orWhere(function ($subQ) {
                    $subQ->where('is_for_rent', true)
                         ->where('rent_price', '>=', (float) $this->minPrice);
                });
            });
        }

        if (!empty($this->maxPrice)) {
            $query->where(function ($q) {
                $q->where(function ($subQ) {
                    $subQ->where('is_for_rent', false)
                         ->where('sell_price', '<=', (float) $this->maxPrice);
                })->orWhere(function ($subQ) {
                    $subQ->where('is_for_rent', true)
                         ->where('rent_price', '<=', (float) $this->maxPrice);
                });
            });
        }

        // Sorting
        switch ($this->sortBy) {
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'price_low':
                $query->orderBy('sell_price', 'asc')->orderBy('rent_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('sell_price', 'desc')->orderBy('rent_price', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        // Get games with pagination
        $games = $query->paginate(12);

        // Load seller information for each game
        foreach ($games as $game) {
            if ($game->seller_id) {
                $game->seller = User::find($game->seller_id);
            }
        }

        return view('livewire.buyer.browse-games', [
            'games' => $games
        ]);
    }
}