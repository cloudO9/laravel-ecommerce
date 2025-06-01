<?php
// app/Livewire/Seller/SalesActivity.php

namespace App\Http\Livewire\Seller;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;
use App\Models\Game;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SalesActivity extends Component
{
    use WithPagination;

    public $typeFilter = '';
    public $gameFilter = '';
    public $dateRange = '30';

    public $totalRevenue = 0;
    public $totalSales = 0;
    public $totalRentals = 0;
    public $recentOrders = 0;

    public $myGames = [];

    protected $queryString = [
        'typeFilter' => ['except' => ''],
        'gameFilter' => ['except' => ''],
        'dateRange' => ['except' => '30'],
        'page' => ['except' => 1],
    ];

    public function mount()
    {
        $this->loadMyGames();
        $this->loadStats();
    }

    public function loadMyGames()
    {
        try {
            $games = Game::where('seller_id', Auth::id())
                        ->orderBy('name')
                        ->get();
            
            $this->myGames = [];
            foreach ($games as $game) {
                $this->myGames[] = [
                    '_id' => $game->_id ?? $game->id,
                    'name' => $game->name ?? 'Unknown Game'
                ];
            }
            
        } catch (\Exception $e) {
            Log::error('Error loading games: ' . $e->getMessage());
            $this->myGames = [];
        }
    }

    public function loadStats()
    {
        try {
            $sellerId = Auth::id();
            
            $dateFrom = match($this->dateRange) {
                '7' => now()->subDays(7),
                '30' => now()->subDays(30),
                '90' => now()->subDays(90),
                'all' => null,
                default => now()->subDays(30)
            };

            $query = Order::query();
            
            if ($dateFrom) {
                $query->where('created_at', '>=', $dateFrom);
            }

            $allOrders = $query->get();
            
            $this->totalRevenue = 0;
            $this->totalSales = 0;
            $this->totalRentals = 0;
            $ordersWithMyItems = 0;

            foreach ($allOrders as $order) {
                $hasMyItems = false;
                $items = $order->items ?? [];
                
                foreach ($items as $item) {
                    $itemSellerId = (string) ($item['seller_id'] ?? '');
                    $currentSellerId = (string) $sellerId;
                    
                    if ($itemSellerId === $currentSellerId) {
                        $hasMyItems = true;
                        $this->totalRevenue += floatval($item['total_price'] ?? 0);
                        
                        $itemType = $item['type'] ?? 'purchase';
                        if ($itemType === 'purchase' || $itemType === 'sale') {
                            $this->totalSales++;
                        } else {
                            $this->totalRentals++;
                        }
                    }
                }
                
                if ($hasMyItems) {
                    $ordersWithMyItems++;
                }
            }
            
            $this->recentOrders = $ordersWithMyItems;

        } catch (\Exception $e) {
            Log::error('Seller stats error: ' . $e->getMessage());
            $this->totalRevenue = 0;
            $this->totalSales = 0;
            $this->totalRentals = 0;
            $this->recentOrders = 0;
        }
    }

    public function updatedTypeFilter()
    {
        $this->resetPage();
    }

    public function updatedGameFilter()
    {
        $this->resetPage();
    }

    public function updatedDateRange()
    {
        $this->loadStats();
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->typeFilter = '';
        $this->gameFilter = '';
        $this->dateRange = '30';
        $this->loadStats();
        $this->resetPage();
        
        session()->flash('message', 'Filters cleared successfully!');
    }

    public function getOrdersProperty()
    {
        try {
            $sellerId = (string) Auth::id();
            
            // Debug: Check if we have any orders at all
            $totalOrdersInDb = Order::count();
            Log::info('Debug: Total orders in database: ' . $totalOrdersInDb);
            Log::info('Debug: Current seller ID: ' . $sellerId);
            
            $query = Order::query();

            $dateFrom = match($this->dateRange) {
                '7' => now()->subDays(7),
                '30' => now()->subDays(30),
                '90' => now()->subDays(90),
                'all' => null,
                default => now()->subDays(30)
            };

            if ($dateFrom) {
                $query->where('created_at', '>=', $dateFrom);
            }

            if ($this->typeFilter) {
                $query->where('type', $this->typeFilter);
            }

            $allOrders = $query->orderBy('created_at', 'desc')->get();
            Log::info('Debug: Orders after date/type filter: ' . $allOrders->count());
            
            // Debug: Check first few orders for seller items
            foreach ($allOrders->take(3) as $order) {
                $items = $order->items ?? [];
                Log::info('Debug: Order ' . $order->order_number . ' has ' . count($items) . ' items');
                foreach ($items as $index => $item) {
                    $itemSellerId = (string) ($item['seller_id'] ?? '');
                    Log::info("Debug: Item $index seller_id: '$itemSellerId' (matches: " . ($itemSellerId === $sellerId ? 'YES' : 'NO') . ")");
                }
            }
            
            $myOrders = $allOrders->filter(function($order) use ($sellerId) {
                $items = $order->items ?? [];
                
                foreach ($items as $item) {
                    $itemSellerId = (string) ($item['seller_id'] ?? '');
                    
                    if ($this->gameFilter) {
                        $itemGameId = (string) ($item['game_id'] ?? '');
                        if ($itemSellerId === $sellerId && $itemGameId === $this->gameFilter) {
                            return true;
                        }
                    } else {
                        if ($itemSellerId === $sellerId) {
                            return true;
                        }
                    }
                }
                return false;
            });
            
            Log::info('Debug: Orders with my items: ' . $myOrders->count());

            $perPage = 10;
            $currentPage = request()->get('page', 1);
            $offset = ($currentPage - 1) * $perPage;
            
            $paginatedOrders = $myOrders->slice($offset, $perPage);
            
            $result = new \Illuminate\Pagination\LengthAwarePaginator(
                $paginatedOrders->values(),
                $myOrders->count(),
                $perPage,
                $currentPage,
                [
                    'path' => request()->url(),
                    'pageName' => 'page'
                ]
            );
            
            return $result;

        } catch (\Exception $e) {
            Log::error('Seller orders query error: ' . $e->getMessage());
            
            return new \Illuminate\Pagination\LengthAwarePaginator(
                collect([]), 
                0, 
                10, 
                1, 
                ['path' => request()->url()]
            );
        }
    }

    public function getMyItemsFromOrder($order)
    {
        $sellerId = (string) Auth::id();
        $myItems = [];
        $items = $order->items ?? [];
        
        foreach ($items as $item) {
            $itemSellerId = (string) ($item['seller_id'] ?? '');
            if ($itemSellerId === $sellerId) {
                $myItems[] = $item;
            }
        }
        return $myItems;
    }

    public function getMyRevenueFromOrder($order)
    {
        $sellerId = (string) Auth::id();
        $revenue = 0;
        $items = $order->items ?? [];
        
        foreach ($items as $item) {
            $itemSellerId = (string) ($item['seller_id'] ?? '');
            if ($itemSellerId === $sellerId) {
                $revenue += floatval($item['total_price'] ?? 0);
            }
        }
        return $revenue;
    }

    public function render()
    {
        return view('livewire.seller.sales-activity', [
            'orders' => $this->orders,
        ]);
    }
}