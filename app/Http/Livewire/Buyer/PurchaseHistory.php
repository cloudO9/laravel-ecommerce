<?php
// Move this file to: app/Livewire/Buyer/PurchaseHistory.php (NOT Http/Livewire)

namespace App\Http\Livewire\Buyer;  // ← CORRECT namespace for Laravel 11

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PurchaseHistory extends Component
{
    use WithPagination;

    public $statusFilter = '';
    public $typeFilter = '';
    public $search = '';
    public $sortBy = 'newest';

    // Stats
    public $totalOrders = 0;
    public $totalPurchases = 0;
    public $totalRentals = 0;
    public $totalSpent = 0;

    protected $queryString = [
        'statusFilter' => ['except' => ''],
        'typeFilter' => ['except' => ''],
        'search' => ['except' => ''],
        'sortBy' => ['except' => 'newest'],
        'page' => ['except' => 1],
    ];

    public function mount()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        try {
            $userId = Auth::id();
            
            $this->totalOrders = Order::where('user_id', $userId)->count();
            $this->totalPurchases = Order::where('user_id', $userId)->where('type', 'purchase')->count();
            $this->totalRentals = Order::where('user_id', $userId)->where('type', 'rental')->count();
            
            $orders = Order::where('user_id', $userId)->get(['total']);
            $this->totalSpent = $orders->sum('total');
            
        } catch (\Exception $e) {
            Log::error('PurchaseHistory loadStats error: ' . $e->getMessage());
            $this->totalOrders = 0;
            $this->totalPurchases = 0;
            $this->totalRentals = 0;
            $this->totalSpent = 0;
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

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSortBy()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->statusFilter = '';
        $this->typeFilter = '';
        $this->search = '';
        $this->sortBy = 'newest';
        $this->resetPage();
        
        session()->flash('message', 'Filters cleared successfully!');
    }

    public function viewOrder($orderId)
    {
        session()->flash('message', 'Order details view will be implemented soon!');
    }

    public function reorder($orderId)
    {
        try {
            $order = Order::where('_id', $orderId)
                         ->where('user_id', Auth::id())
                         ->first();

            if (!$order) {
                session()->flash('error', 'Order not found.');
                return;
            }

            session()->flash('message', ' Reorder functionality will be implemented soon!');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to reorder items.');
            Log::error('Reorder error: ' . $e->getMessage());
        }
    }

    public function downloadInvoice($orderId)
    {
        session()->flash('message', 'Invoice download will be available soon!');
    }

    public function getOrdersProperty()
    {
        try {
            $userId = Auth::id();
            $query = Order::where('user_id', $userId);

            if ($this->search) {
                $query->where('order_number', 'like', '%' . $this->search . '%');
            }

            if ($this->statusFilter) {
                $query->where('status', $this->statusFilter);
            }

            if ($this->typeFilter) {
                $query->where('type', $this->typeFilter);
            }

            switch ($this->sortBy) {
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'amount_high':
                    $query->orderBy('total', 'desc');
                    break;
                case 'amount_low':
                    $query->orderBy('total', 'asc');
                    break;
                case 'newest':
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }

            return $query->paginate(10);
            
        } catch (\Exception $e) {
            Log::error('getOrdersProperty error: ' . $e->getMessage());
            
            return new \Illuminate\Pagination\LengthAwarePaginator(
                collect([]), 
                0, 
                10, 
                1, 
                [
                    'path' => request()->url(),
                    'pageName' => 'page'
                ]
            );
        }
    }

    public function render()
    {
        return view('livewire.buyer.purchasehistory', [  
            'orders' => $this->orders,
            'totalOrders' => $this->totalOrders,
            'totalPurchases' => $this->totalPurchases,
            'totalRentals' => $this->totalRentals,
            'totalSpent' => $this->totalSpent,
            'search' => $this->search,
            'statusFilter' => $this->statusFilter,
            'typeFilter' => $this->typeFilter,
            'sortBy' => $this->sortBy,
        ]);
    }
}