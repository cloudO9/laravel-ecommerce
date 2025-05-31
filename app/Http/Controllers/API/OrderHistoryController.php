<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderHistoryController extends Controller
{
    /**
     * Get buyer's purchase statistics
     */
    public function buyerStats()
    {
        try {
            $userId = Auth::id();
            
            $totalOrders = Order::where('user_id', $userId)->count();
            $totalPurchases = Order::where('user_id', $userId)->where('type', 'purchase')->count();
            $totalRentals = Order::where('user_id', $userId)->where('type', 'rental')->count();
            
            // Calculate total spent
            $orders = Order::where('user_id', $userId)->get(['total']);
            $totalSpent = $orders->sum('total');

            return response()->json([
                'success' => true,
                'stats' => [
                    'total_orders' => $totalOrders,
                    'total_purchases' => $totalPurchases,
                    'total_rentals' => $totalRentals,
                    'total_spent' => round($totalSpent, 2)
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Buyer stats error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get seller's sales statistics
     */
    public function sellerStats(Request $request)
    {
        try {
            $sellerId = Auth::id();
            $dateRange = $request->get('date_range', '30'); // 7, 30, 90, or 'all'
            
            // Get date range
            $dateFrom = match($dateRange) {
                '7' => now()->subDays(7),
                '30' => now()->subDays(30),
                '90' => now()->subDays(90),
                'all' => null,
                default => now()->subDays(30)
            };

            // Get all orders in date range
            $query = Order::query();
            if ($dateFrom) {
                $query->where('created_at', '>=', $dateFrom);
            }
            $allOrders = $query->get();
            
            // Calculate stats for seller's items
            $totalRevenue = 0;
            $totalSales = 0;
            $totalRentals = 0;
            $ordersWithMyItems = 0;

            foreach ($allOrders as $order) {
                $hasMyItems = false;
                $items = $order->items ?? [];
                
                foreach ($items as $item) {
                    $itemSellerId = (string) ($item['seller_id'] ?? '');
                    $currentSellerId = (string) $sellerId;
                    
                    if ($itemSellerId === $currentSellerId) {
                        $hasMyItems = true;
                        $totalRevenue += floatval($item['total_price'] ?? 0);
                        
                        $itemType = $item['type'] ?? 'purchase';
                        if ($itemType === 'purchase' || $itemType === 'sale') {
                            $totalSales++;
                        } else {
                            $totalRentals++;
                        }
                    }
                }
                
                if ($hasMyItems) {
                    $ordersWithMyItems++;
                }
            }

            return response()->json([
                'success' => true,
                'stats' => [
                    'total_revenue' => round($totalRevenue, 2),
                    'total_sales' => $totalSales,
                    'total_rentals' => $totalRentals,
                    'recent_orders' => $ordersWithMyItems,
                    'date_range' => $dateRange
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Seller stats error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get seller's games for filter dropdown
     */
    public function sellerGames()
    {
        try {
            $games = Game::where('seller_id', Auth::id())
                        ->orderBy('name')
                        ->get(['_id', 'name']);
            
            $gamesList = [];
            foreach ($games as $game) {
                $gamesList[] = [
                    'id' => $game->_id,
                    'name' => $game->name
                ];
            }

            return response()->json([
                'success' => true,
                'games' => $gamesList
            ]);

        } catch (\Exception $e) {
            Log::error('Seller games error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load games',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get buyer's purchase history
     */
    public function buyerOrders(Request $request)
    {
        try {
            $userId = Auth::id();
            $page = $request->get('page', 1);
            $perPage = $request->get('per_page', 10);
            
            // Filters
            $statusFilter = $request->get('status');
            $typeFilter = $request->get('type');
            $search = $request->get('search');
            $sortBy = $request->get('sort_by', 'newest');

            $query = Order::where('user_id', $userId);

            // Search filter
            if ($search) {
                $query->where('order_number', 'like', '%' . $search . '%');
            }

            // Status filter
            if ($statusFilter) {
                $query->where('status', $statusFilter);
            }

            // Type filter
            if ($typeFilter) {
                $query->where('type', $typeFilter);
            }

            // Sorting
            switch ($sortBy) {
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

            // Paginate
            $orders = $query->paginate($perPage, ['*'], 'page', $page);

            // Transform orders for API response
            $transformedOrders = $orders->map(function($order) {
                return [
                    'id' => $order->_id,
                    'order_number' => $order->order_number,
                    'type' => $order->type,
                    'status' => $order->status,
                    'total' => $order->total,
                    'formatted_total' => '$' . number_format($order->total, 2),
                    'items_count' => count($order->items ?? []),
                    'items' => $order->items,
                    'created_at' => $order->created_at,
                    'shipping_address' => $order->shipping_address,
                    'contact_info' => $order->contact_info
                ];
            });

            return response()->json([
                'success' => true,
                'orders' => $transformedOrders,
                'pagination' => [
                    'current_page' => $orders->currentPage(),
                    'per_page' => $orders->perPage(),
                    'total' => $orders->total(),
                    'total_pages' => $orders->lastPage(),
                    'has_more' => $orders->hasMorePages()
                ],
                'filters' => [
                    'status' => $statusFilter,
                    'type' => $typeFilter,
                    'search' => $search,
                    'sort_by' => $sortBy
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Buyer orders error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load purchase history',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get seller's sales orders
     */
    public function sellerOrders(Request $request)
    {
        try {
            $sellerId = (string) Auth::id();
            $page = $request->get('page', 1);
            $perPage = $request->get('per_page', 10);
            
            // Filters
            $statusFilter = $request->get('status');
            $typeFilter = $request->get('type');
            $gameFilter = $request->get('game_id');
            $dateRange = $request->get('date_range', '30');

            // Date range filter
            $query = Order::query();
            $dateFrom = match($dateRange) {
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
            if ($statusFilter) {
                $query->where('status', $statusFilter);
            }

            // Type filter
            if ($typeFilter) {
                $query->where('type', $typeFilter);
            }

            // Get all orders and filter manually for seller's items
            $allOrders = $query->orderBy('created_at', 'desc')->get();
            
            // Filter orders that contain seller's items
            $myOrders = $allOrders->filter(function($order) use ($sellerId, $gameFilter) {
                $items = $order->items ?? [];
                
                foreach ($items as $item) {
                    $itemSellerId = (string) ($item['seller_id'] ?? '');
                    
                    // Check if this item belongs to the seller
                    if ($itemSellerId === $sellerId) {
                        // If game filter is set, also check game_id
                        if ($gameFilter) {
                            $itemGameId = (string) ($item['game_id'] ?? '');
                            return $itemGameId === $gameFilter;
                        }
                        return true;
                    }
                }
                return false;
            });

            // Manual pagination
            $offset = ($page - 1) * $perPage;
            $paginatedOrders = $myOrders->slice($offset, $perPage);
            
            // Transform orders to include only seller's items and revenue
            $transformedOrders = $paginatedOrders->map(function($order) use ($sellerId) {
                $myItems = [];
                $myRevenue = 0;
                
                foreach ($order->items ?? [] as $item) {
                    $itemSellerId = (string) ($item['seller_id'] ?? '');
                    if ($itemSellerId === $sellerId) {
                        $myItems[] = $item;
                        $myRevenue += floatval($item['total_price'] ?? 0);
                    }
                }
                
                return [
                    'id' => $order->_id,
                    'order_number' => $order->order_number,
                    'type' => $order->type,
                    'status' => $order->status,
                    'total' => $order->total,
                    'my_revenue' => round($myRevenue, 2),
                    'my_items' => $myItems,
                    'created_at' => $order->created_at,
                    'shipping_address' => $order->shipping_address,
                    'contact_info' => $order->contact_info
                ];
            });

            // Create pagination data
            $totalCount = $myOrders->count();
            $hasMore = ($offset + $perPage) < $totalCount;
            $totalPages = ceil($totalCount / $perPage);

            return response()->json([
                'success' => true,
                'orders' => $transformedOrders->values(),
                'pagination' => [
                    'current_page' => (int) $page,
                    'per_page' => (int) $perPage,
                    'total' => $totalCount,
                    'total_pages' => $totalPages,
                    'has_more' => $hasMore
                ],
                'filters' => [
                    'status' => $statusFilter,
                    'type' => $typeFilter,
                    'game_id' => $gameFilter,
                    'date_range' => $dateRange
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Seller orders error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load orders',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get order details (works for both buyers and sellers)
     */
    public function show($orderId, Request $request)
    {
        try {
            $userId = Auth::id();
            $viewAs = $request->get('view_as', 'buyer'); // 'buyer' or 'seller'
            
            $order = Order::where('_id', $orderId)->first();
            
            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found'
                ], 404);
            }

            if ($viewAs === 'buyer') {
                // Buyer view - check if they own the order
                if ((string)$order->user_id !== (string)$userId) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Access denied'
                    ], 403);
                }

                return response()->json([
                    'success' => true,
                    'order' => [
                        'id' => $order->_id,
                        'order_number' => $order->order_number,
                        'type' => $order->type,
                        'status' => $order->status,
                        'total' => $order->total,
                        'formatted_total' => '$' . number_format($order->total, 2),
                        'subtotal' => $order->subtotal,
                        'items' => $order->items,
                        'items_count' => count($order->items ?? []),
                        'created_at' => $order->created_at,
                        'updated_at' => $order->updated_at,
                        'shipping_address' => $order->shipping_address,
                        'contact_info' => $order->contact_info,
                        'payment_info' => $order->payment_info,
                        'rental_start_date' => $order->rental_start_date,
                        'rental_end_date' => $order->rental_end_date,
                        'notes' => $order->notes
                    ]
                ]);

            } else {
                // Seller view - check if they have items in this order
                $sellerId = (string) $userId;
                $myItems = [];
                $myRevenue = 0;
                
                foreach ($order->items ?? [] as $item) {
                    $itemSellerId = (string) ($item['seller_id'] ?? '');
                    if ($itemSellerId === $sellerId) {
                        $myItems[] = $item;
                        $myRevenue += floatval($item['total_price'] ?? 0);
                    }
                }

                if (empty($myItems)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You do not have items in this order'
                    ], 403);
                }

                return response()->json([
                    'success' => true,
                    'order' => [
                        'id' => $order->_id,
                        'order_number' => $order->order_number,
                        'type' => $order->type,
                        'status' => $order->status,
                        'total' => $order->total,
                        'my_revenue' => round($myRevenue, 2),
                        'my_items' => $myItems,
                        'created_at' => $order->created_at,
                        'shipping_address' => $order->shipping_address,
                        'contact_info' => $order->contact_info,
                        'payment_info' => $order->payment_info
                    ]
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Order show error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load order details',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel an order (buyer only)
     */
    public function cancel($orderId)
    {
        try {
            $order = Order::where('_id', $orderId)
                         ->where('user_id', Auth::id())
                         ->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found'
                ], 404);
            }

            // Check if order can be cancelled
            if (in_array($order->status, ['shipped', 'delivered', 'cancelled'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'This order cannot be cancelled'
                ], 422);
            }

            $order->update(['status' => 'cancelled']);

            return response()->json([
                'success' => true,
                'message' => 'Order cancelled successfully',
                'order' => [
                    'id' => $order->_id,
                    'order_number' => $order->order_number,
                    'status' => $order->status
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Order cancellation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel order',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}