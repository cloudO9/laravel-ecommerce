<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        // Get the authenticated user
        $user = Auth::user();
        
        // Check if it's an admin (you might need to adjust this logic)
        if ($user instanceof \App\Models\Admin) {
            return redirect()->route('admin.dashboard');
        }
        
        // For regular users, redirect based on role
        if (isset($user->role)) {
            return redirect()->route($user->role . '.dashboard');
        }
        
        // Default fallback
        return redirect()->route('dashboard');
    }
}