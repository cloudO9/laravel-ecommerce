<?php

namespace App\View\Components;

use Illuminate\View\Component;

class GuestLayout extends Component
{
    public function render()
    {
        return view('components.layouts.guest'); // ✅ Correct path
    }
}