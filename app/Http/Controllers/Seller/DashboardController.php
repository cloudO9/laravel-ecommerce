<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        // You can return a view here when ready
        return view('seller.dashboard'); // create this view later
    }
}
