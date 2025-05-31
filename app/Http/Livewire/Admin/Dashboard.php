<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Game;
use App\Models\Order;

class Dashboard extends Component
{
    public $totalUsers;
    public $totalGames;
    public $totalOrders;
    public $totalRevenue;
    public $recentUsers;
    public $recentGames;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        // Get statistics
        $this->totalUsers = User::count();
        $this->totalGames = Game::count();
        $this->totalOrders = Order::count();
        $this->totalRevenue = Order::where('status', 'delivered')->sum('total');

        // Get recent data
        $this->recentUsers = User::orderBy('created_at', 'desc')->limit(5)->get();
        $this->recentGames = Game::orderBy('created_at', 'desc')->limit(5)->get();
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}