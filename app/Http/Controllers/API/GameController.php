<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\GameStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class GameController extends Controller
{
    /**
     * Display a listing of the games.
     */
    public function index(Request $request)
    {
        // Build query with filters
        $query = Game::query();
        
        // Apply search filter
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        // Apply type filter
        if ($request->has('type')) {
            if ($request->type === 'rent') {
                $query->where('is_for_rent', true);
            } elseif ($request->type === 'sale') {
                $query->where('is_for_rent', false);
            }
        }
        
        // Apply condition filter
        if ($request->has('condition')) {
            $query->where('condition', $request->condition);
        }
        
        // Apply price range filter
        if ($request->has('min_price') || $request->has('max_price')) {
            $query->priceRange(
                $request->min_price ?? null,
                $request->max_price ?? null
            );
        }
        
        // Get games with seller info (but limit fields for privacy)
        $games = $query->with('seller:_id,name')->paginate(12);
        
        // Add formatted prices and status to each game
        $games->getCollection()->transform(function ($game) {
            $game->formatted_price = $game->getFormattedPrice();
            $game->type_display = $game->getTypeDisplay();
            $game->status_display = $game->getStatusDisplay();
            return $game;
        });
        
        return response()->json($games);
    }

    /**
     * Store a newly created game in storage.
     */
    public function store(Request $request)
    {
        // Create validator
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'image' => 'required|image|max:2048',
            'is_for_rent' => 'required|boolean',
            'condition' => 'required|string|in:New,Like New,Very Good,Good,Fair,Poor',
            'description' => 'nullable|string|max:1000',
        ]);
        
        // Add conditional price validation
        if ($request->is_for_rent) {
            $validator->addRules(['rent_price' => 'required|numeric|min:0.01|max:999.99']);
        } else {
            $validator->addRules(['sell_price' => 'required|numeric|min:0.01|max:9999.99']);
        }
        
        // Check validation
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            // Handle image upload
            $imagePath = $request->file('image')->store('games', 'public');
            
            // Prepare game data
            $gameData = [
                'name' => $request->name,
                'image' => $imagePath,
                'is_for_rent' => $request->is_for_rent,
                'condition' => $request->condition,
                'description' => $request->description,
                'seller_id' => Auth::id(),
            ];
            
            // Set appropriate price field based on rental status
            if ($request->is_for_rent) {
                $gameData['rent_price'] = (float) $request->rent_price;
            } else {
                $gameData['sell_price'] = (float) $request->sell_price;
            }
            
            // Create game
            $game = Game::create($gameData);
            
            // Add formatted price and type to response
            $game->formatted_price = $game->getFormattedPrice();
            $game->type_display = $game->getTypeDisplay();
            
            return response()->json([
                'success' => true,
                'message' => 'Game created successfully',
                'game' => $game
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create game',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified game.
     */
    public function show($id)
    {
        try {
            $game = Game::with('seller:_id,name')->find($id);
            
            if (!$game) {
                return response()->json([
                    'success' => false,
                    'message' => 'Game not found'
                ], 404);
            }
            
            // Add formatted prices and status
            $game->formatted_price = $game->getFormattedPrice();
            $game->type_display = $game->getTypeDisplay();
            $game->status_display = $game->getStatusDisplay();
            
            return response()->json([
                'success' => true,
                'game' => $game
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve game',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified game in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $game = Game::where('_id', $id)
                       ->where('seller_id', Auth::id())
                       ->first();
            
            if (!$game) {
                return response()->json([
                    'success' => false,
                    'message' => 'Game not found or you do not have permission to edit it'
                ], 404);
            }
            
            // Validate basic fields
            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'is_for_rent' => 'sometimes|required|boolean',
                'condition' => 'sometimes|required|string|in:New,Like New,Very Good,Good,Fair,Poor',
                'description' => 'nullable|string|max:1000',
                'image' => 'nullable|image|max:2048',
            ]);
            
            // Add conditional price validation
            if ($request->has('is_for_rent')) {
                if ($request->is_for_rent) {
                    $validator->addRules(['rent_price' => 'required|numeric|min:0.01|max:999.99']);
                } else {
                    $validator->addRules(['sell_price' => 'required|numeric|min:0.01|max:9999.99']);
                }
            } elseif ($game->is_for_rent) {
                $validator->addRules(['rent_price' => 'sometimes|required|numeric|min:0.01|max:999.99']);
            } else {
                $validator->addRules(['sell_price' => 'sometimes|required|numeric|min:0.01|max:9999.99']);
            }
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            
            // Update fields
            $updateData = [];
            
            if ($request->has('name')) {
                $updateData['name'] = $request->name;
            }
            
            if ($request->has('description')) {
                $updateData['description'] = $request->description;
            }
            
            if ($request->has('condition')) {
                $updateData['condition'] = $request->condition;
            }
            
            // Handle rental/sale status change
            if ($request->has('is_for_rent')) {
                $updateData['is_for_rent'] = $request->is_for_rent;
                
                if ($request->is_for_rent) {
                    $updateData['rent_price'] = (float) $request->rent_price;
                    $updateData['sell_price'] = null;
                } else {
                    $updateData['sell_price'] = (float) $request->sell_price;
                    $updateData['rent_price'] = null;
                }
            } else {
                // Just update the appropriate price field
                if ($game->is_for_rent && $request->has('rent_price')) {
                    $updateData['rent_price'] = (float) $request->rent_price;
                } elseif (!$game->is_for_rent && $request->has('sell_price')) {
                    $updateData['sell_price'] = (float) $request->sell_price;
                }
            }
            
            // Handle image upload if provided
            if ($request->hasFile('image')) {
                // Delete old image
                if ($game->image && Storage::exists('public/' . $game->image)) {
                    Storage::delete('public/' . $game->image);
                }
                
                $updateData['image'] = $request->file('image')->store('games', 'public');
            }
            
            // Update the game
            $game->update($updateData);
            
            // Refresh game from DB
            $game = Game::find($id);
            
            // Add formatted price and type to response
            $game->formatted_price = $game->getFormattedPrice();
            $game->type_display = $game->getTypeDisplay();
            
            return response()->json([
                'success' => true,
                'message' => 'Game updated successfully',
                'game' => $game
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update game',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified game from storage.
     */
    public function destroy($id)
    {
        try {
            $game = Game::where('_id', $id)
                       ->where('seller_id', Auth::id())
                       ->first();
            
            if (!$game) {
                return response()->json([
                    'success' => false,
                    'message' => 'Game not found or you do not have permission to delete it'
                ], 404);
            }
            
            $gameName = $game->name;
            
            // Delete image if exists
            if ($game->image && Storage::exists('public/' . $game->image)) {
                Storage::delete('public/' . $game->image);
            }
            
            // Delete game status first
            GameStatus::where('game_id', (string) $id)->delete();
            
            // Delete the game
            $game->delete();
            
            return response()->json([
                'success' => true,
                'message' => "Game '{$gameName}' has been deleted successfully"
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete game',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update game status (available, rented, sold)
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'status' => 'required|string|in:available,rented,sold'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $game = Game::where('_id', $id)
                       ->where('seller_id', Auth::id())
                       ->first();
            
            if (!$game) {
                return response()->json([
                    'success' => false,
                    'message' => 'Game not found or you do not have permission to update its status'
                ], 404);
            }
            
            // Check if trying to make sold game available again
            if ($game->isSold() && $request->status === 'available') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot make sold games available again. Please create a new listing.'
                ], 422);
            }
            
            // Update or create status
            GameStatus::updateOrCreate(
                ['game_id' => $game->_id],
                ['status' => $request->status]
            );
            
            // Refresh game from DB
            $game = Game::find($id);
            
            return response()->json([
                'success' => true,
                'message' => 'Game status updated successfully',
                'status' => $request->status,
                'status_display' => $game->getStatusDisplay()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update game status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search games by name
     */
    public function search(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'query' => 'required|string|min:2'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $games = Game::where('name', 'like', '%' . $request->query . '%')
                        ->with('seller:_id,name')
                        ->paginate(12);
            
            // Add formatted prices and status to each game
            $games->getCollection()->transform(function ($game) {
                $game->formatted_price = $game->getFormattedPrice();
                $game->type_display = $game->getTypeDisplay();
                $game->status_display = $game->getStatusDisplay();
                return $game;
            });
            
            return response()->json([
                'success' => true,
                'games' => $games
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Search failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get games for a specific seller
     */
    public function sellerGames($sellerId)
    {
        try {
            $games = Game::where('seller_id', $sellerId)
                        ->with('seller:_id,name')
                        ->paginate(12);
            
            // Add formatted prices and status to each game
            $games->getCollection()->transform(function ($game) {
                $game->formatted_price = $game->getFormattedPrice();
                $game->type_display = $game->getTypeDisplay();
                $game->status_display = $game->getStatusDisplay();
                return $game;
            });
            
            return response()->json([
                'success' => true,
                'games' => $games
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve seller games',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get current user's games
     */
    public function myGames()
    {
        try {
            $games = Game::where('seller_id', Auth::id())
                        ->paginate(12);
            
            // Add formatted prices and status to each game
            $games->getCollection()->transform(function ($game) {
                $game->formatted_price = $game->getFormattedPrice();
                $game->type_display = $game->getTypeDisplay();
                $game->status_display = $game->getStatusDisplay();
                return $game;
            });
            
            return response()->json([
                'success' => true,
                'games' => $games
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve your games',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}