<?php

namespace App\Http\Livewire\Seller;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Game;
use Illuminate\Support\Facades\Auth;

class PostGame extends Component
{
    use WithFileUploads;

    public $name;
    public $image;
    public $is_for_rent = 0; // Default to 0 (for sale)
    public $rent_price;
    public $sell_price;
    public $condition;
    public $description;

    // This method runs when the is_for_rent radio button changes
    public function updatedIsForRent($value)
    {
        // Convert string to integer for consistency
        $this->is_for_rent = (int) $value;
        
        // Clear the opposite price field when switching
        if ($this->is_for_rent === 1) {
            // Switching to rent - clear sell price
            $this->sell_price = null;
        } else {
            // Switching to sell - clear rent price
            $this->rent_price = null;
        }
    }

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'image' => 'required|image|max:2048', // 2MB max
            'condition' => 'required|string|in:New,Like New,Very Good,Good,Fair,Poor',
            'description' => 'nullable|string|max:1000',
        ];

        // Add conditional price validation based on the current selection
        if ($this->is_for_rent == 1) {
            $rules['rent_price'] = 'required|numeric|min:0.01|max:999.99';
        } else {
            $rules['sell_price'] = 'required|numeric|min:0.01|max:9999.99';
        }

        return $rules;
    }

    protected $messages = [
        'name.required' => 'Game name is required.',
        'image.required' => 'Game image is required.',
        'image.image' => 'The file must be an image.',
        'image.max' => 'Image size cannot exceed 2MB.',
        'condition.required' => 'Please select the game condition.',
        'condition.in' => 'Please select a valid condition.',
        'rent_price.required' => 'Rent price is required when listing for rent.',
        'rent_price.min' => 'Rent price must be at least $0.01.',
        'rent_price.max' => 'Rent price cannot exceed $999.99.',
        'sell_price.required' => 'Sell price is required when listing for sale.',
        'sell_price.min' => 'Sell price must be at least $0.01.',
        'sell_price.max' => 'Sell price cannot exceed $9999.99.',
        'description.max' => 'Description cannot exceed 1000 characters.',
    ];

    public function postGame()
    {
        try {
            // Validate the form data
            $validated = $this->validate();

            // Store image in storage/app/public/games
            $imagePath = $this->image->store('games', 'public');

            // Prepare base data for database
            $gameData = [
                'name' => trim($this->name),
                'image' => $imagePath,
                'is_for_rent' => $this->is_for_rent == 1,
                'condition' => $this->condition,
                'description' => trim($this->description) ?: null,
                'seller_id' => Auth::id(),
            ];

            // Handle prices safely - only set the relevant price field
            if ($this->is_for_rent == 1) {
                // For rent
                $gameData['rent_price'] = !empty($this->rent_price) ? (float) $this->rent_price : null;
                // Don't set sell_price at all, let it default to null
            } else {
                // For sale
                $gameData['sell_price'] = !empty($this->sell_price) ? (float) $this->sell_price : null;
                // Don't set rent_price at all, let it default to null
            }

            // Create game record
            $game = Game::create($gameData);

            // Reset form fields after successful submission
            $this->reset([
                'name', 
                'image', 
                'is_for_rent', 
                'rent_price', 
                'sell_price', 
                'condition', 
                'description'
            ]);

            // Reset to default (for sale)
            $this->is_for_rent = 0;

            // Set success message
            $gameType = $game->is_for_rent ? 'rental' : 'sale';
            $price = $game->getFormattedPrice();
            
            session()->flash('message', "Game '{$game->name}' posted successfully for {$gameType} at {$price}!");

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Re-throw validation exceptions to show field errors
            throw $e;
        } catch (\Exception $e) {
            // Handle other errors
            session()->flash('error', 'Failed to post game. Please try again.');
            \Log::error('Game posting error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.seller.post-game');
    }
}