<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        // You can return a view here when ready
        return view('buyer.dashboard'); // create this view later
    }
}
