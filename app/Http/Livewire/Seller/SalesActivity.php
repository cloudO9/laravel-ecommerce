<?php
// app/Livewire/Seller/SalesActivity.php

namespace App\Http\Livewire\Seller;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;
use App\Models\Game;
use Illuminate\Support\Facades\Auth;

class SalesActivity extends Component
{
    use WithPagination;

    public $statusFilter = '';
    public $typeFilter = '';
    public $gameFilter = '';
    public $dateRange = '30'; // Last 30 days by default

    // Stats
    public $totalRevenue = 0;
    public $totalSales = 0;
    public $totalRentals = 0;
    public $recentOrders = 0;

    // Games for filter dropdown
    public $myGames = [];

    protected $queryString = [
        'statusFilter' => ['except' => ''],
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
            
            \Log::info('Loaded games for seller', [
                'seller_id' => Auth::id(),
                'games_count' => count($this->myGames),
                'games' => $this->myGames
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error loading games: ' . $e->getMessage());
            $this->myGames = [];
        }
    }

    public function loadStats()
    {
        try {
            $sellerId = Auth::id();
            
            // Get date range
            $dateFrom = match($this->dateRange) {
                '7' => now()->subDays(7),
                '30' => now()->subDays(30),
                '90' => now()->subDays(90),
                'all' => null,
                default => now()->subDays(30)
            };

            // Get all orders in date range, then filter by seller items
            $query = Order::query();
            
            if ($dateFrom) {
                $query->where('created_at', '>=', $dateFrom);
            }

            $allOrders = $query->get();
            
            // Filter orders that contain my items and calculate stats
            $this->totalRevenue = 0;
            $this->totalSales = 0;
            $this->totalRentals = 0;
            $ordersWithMyItems = 0;

            foreach ($allOrders as $order) {
                $hasMyItems = false;
                $items = $order->items ?? [];
                
                foreach ($items as $item) {
                    // Convert both to string for comparison
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

            \Log::info('Stats loaded', [
                'sellerId' => $sellerId,
                'totalRevenue' => $this->totalRevenue,
                'totalSales' => $this->totalSales,
                'totalRentals' => $this->totalRentals,
                'recentOrders' => $this->recentOrders,
                'dateRange' => $this->dateRange,
                'totalOrdersChecked' => $allOrders->count()
            ]);

        } catch (\Exception $e) {
            \Log::error('Seller stats error: ' . $e->getMessage());
            \Log::error('Error trace: ' . $e->getTraceAsString());
            $this->totalRevenue = 0;
            $this->totalSales = 0;
            $this->totalRentals = 0;
            $this->recentOrders = 0;
        }
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
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
        $this->statusFilter = '';
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
            
            // Start with base query like buyer's implementation
            $query = Order::query();

            // Date range filter
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

            // Status filter
            if ($this->statusFilter) {
                $query->where('status', $this->statusFilter);
            }

            // Type filter
            if ($this->typeFilter) {
                $query->where('type', $this->typeFilter);
            }

            // Get all orders and filter manually (like buyer's simple approach)
            $allOrders = $query->orderBy('created_at', 'desc')->get();
            
            // Filter orders that contain my items
            $myOrders = $allOrders->filter(function($order) use ($sellerId) {
                $items = $order->items ?? [];
                
                foreach ($items as $item) {
                    $itemSellerId = (string) ($item['seller_id'] ?? '');
                    
                    // If game filter is set, also check game_id
                    if ($this->gameFilter) {
                        $itemGameId = (string) ($item['game_id'] ?? '');
                        if ($itemSellerId === $sellerId && $itemGameId === $this->gameFilter) {
                            return true;
                        }
                    } else {
                        // No game filter, just check seller_id
                        if ($itemSellerId === $sellerId) {
                            return true;
                        }
                    }
                }
                return false;
            });

            // Manual pagination (like in your buyer's fallback)
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
            
            \Log::info('Orders query result', [
                'sellerId' => $sellerId,
                'totalOrdersInDb' => $allOrders->count(),
                'ordersWithMyItems' => $myOrders->count(),
                'paginatedCount' => $paginatedOrders->count(),
                'filters' => [
                    'status' => $this->statusFilter,
                    'type' => $this->typeFilter,
                    'game' => $this->gameFilter,
                    'dateRange' => $this->dateRange
                ]
            ]);
            
            return $result;

        } catch (\Exception $e) {
            \Log::error('Seller orders query error: ' . $e->getMessage());
            \Log::error('Error trace: ' . $e->getTraceAsString());
            
            return new \Illuminate\Pagination\LengthAwarePaginator(
                collect([]), 
                0, 
                10, 
                1, 
                ['path' => request()->url()]
            );
        }
    }

    // Helper to get only my items from an order
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

    // Helper to calculate my revenue from an order
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

    // Debug method - temporary
    public function debugData()
    {
        $sellerId = Auth::id();
        
        \Log::info('=== DEBUGGING SELLER DATA ===');
        \Log::info('Current Seller ID: ' . $sellerId);
        \Log::info('Seller ID Type: ' . gettype($sellerId));
        
        // Get a few recent orders to examine structure
        $orders = Order::orderBy('created_at', 'desc')->limit(5)->get();
        
        foreach ($orders as $order) {
            \Log::info('Order: ' . $order->order_number, [
                'order_id' => $order->_id,
                'user_id' => $order->user_id,
                'type' => $order->type,
                'status' => $order->status,
                'total' => $order->total,
                'items_count' => count($order->items ?? [])
            ]);
            
            foreach ($order->items ?? [] as $index => $item) {
                \Log::info("  Item $index:", [
                    'seller_id' => $item['seller_id'] ?? 'MISSING',
                    'seller_id_type' => gettype($item['seller_id'] ?? null),
                    'game_id' => $item['game_id'] ?? 'MISSING',
                    'game_name' => $item['game_name'] ?? 'MISSING',
                    'total_price' => $item['total_price'] ?? 'MISSING',
                    'type' => $item['type'] ?? 'MISSING',
                    'quantity' => $item['quantity'] ?? 'MISSING',
                    'matches_me' => ((string)($item['seller_id'] ?? '') === (string)$sellerId) ? 'YES' : 'NO'
                ]);
            }
        }
    }

    public function render()
    {
        return view('livewire.seller.sales-activity', [
            'orders' => $this->orders,
        ]);
    }
}