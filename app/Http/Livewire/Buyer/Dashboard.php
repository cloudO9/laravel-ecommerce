<?php

namespace App\Http\Livewire\Buyer;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Game;
use App\Models\User;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    use WithPagination;

    // Search and filter properties
    public $search = '';
    public $conditionFilter = '';
    public $typeFilter = '';
    public $minPrice = '';
    public $maxPrice = '';
    public $sortBy = 'newest';

    // Stats
    public $totalGames = 0;
    public $gamesForRent = 0;
    public $gamesForSale = 0;

    // Cart related
    public $cartCount = 0;

    // Listeners for cart updates
    protected $listeners = ['cartUpdated' => 'updateCartCount'];

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

    // View specific game
    public function viewGame($gameId)
    {
        return redirect()->route('buyer.game-detail', ['gameId' => $gameId]);
    }

    // Add to cart function
    public function addToCart($gameId, $rentalDays = 1)
    {
        if (!Auth::check()) {
            session()->flash('error', 'Please login to add items to cart.');
            return;
        }

        try {
            $game = Game::find($gameId);
            if (!$game) {
                session()->flash('error', 'Game not found.');
                return;
            }

            // Check if it's the user's own game
            if ($game->seller_id === Auth::id()) {
                session()->flash('error', 'You cannot add your own game to cart.');
                return;
            }

            // Check if game is still available (not sold, but rented is OK for viewing)
            if ($game->isSold()) {
                session()->flash('error', 'This game has been sold and is no longer available.');
                return;
            }

            Cart::addToCart(Auth::id(), $gameId, 1, $rentalDays);
            
            $this->updateCartCount();
            $this->emit('cartUpdated');
            
            $message = $game->is_for_rent ? 
                "🛒 '{$game->name}' added to cart for {$rentalDays} days!" :
                "🛒 '{$game->name}' added to cart!";
                
            session()->flash('message', $message);
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to add item to cart.');
        }
    }

    // Update cart count
    public function updateCartCount()
    {
        if (Auth::check()) {
            $this->cartCount = Cart::getCountForUser(Auth::id());
        } else {
            $this->cartCount = 0;
        }
    }

    // Mount method to load initial stats
    public function mount()
    {
        $this->loadStats();
        $this->updateCartCount();
    }

    // UPDATED: Load statistics - exclude sold games but keep rented ones
    public function loadStats()
    {
        try {
            // Get all games from other sellers first
            $otherSellersGames = Game::where('seller_id', '!=', Auth::id())->get();
            
            // Filter to exclude only SOLD games (keep available and rented)
            $nonSoldGames = $otherSellersGames->filter(function ($game) {
                return !$game->isSold(); // Exclude only sold games
            });
            
            $this->totalGames = $nonSoldGames->count();
            $this->gamesForRent = $nonSoldGames->where('is_for_rent', true)->count();
            $this->gamesForSale = $nonSoldGames->where('is_for_rent', false)->count();
        } catch (\Exception $e) {
            $this->totalGames = 0;
            $this->gamesForRent = 0;
            $this->gamesForSale = 0;
        }
    }

    public function render()
    {
        try {
            // UPDATED: Start building the query - exclude current user's games
            $query = Game::query()
                        ->where('seller_id', '!=', Auth::id()); // Only show games from other sellers

            // Search by name (case-insensitive for MongoDB)
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

            // UPDATED: Load seller info and filter to exclude only SOLD games
            $filteredGames = collect();
            
            foreach ($games as $game) {
                // Load seller information
                if ($game->seller_id) {
                    $game->seller = User::find($game->seller_id);
                }
                
                // Only exclude SOLD games (keep available and rented games)
                if (!$game->isSold()) {
                    $filteredGames->push($game);
                }
            }

            // Create a new paginated collection excluding sold games
            $nonSoldGames = new \Illuminate\Pagination\LengthAwarePaginator(
                $filteredGames,
                $filteredGames->count(),
                12,
                request()->get('page', 1),
                [
                    'path' => request()->url(),
                    'pageName' => 'page',
                ]
            );

            return view('livewire.buyer.dashboard', [
                'games' => $nonSoldGames
            ]);

        } catch (\Exception $e) {
            // If there's any error, return empty collection
            return view('livewire.buyer.dashboard', [
                'games' => collect()->paginate(12)
            ]);
        }
    }
}