<?php

namespace App\Actions\Fortify;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\Admin;
use App\Models\User;

class AuthenticateUser
{
    public function __invoke(Request $request)
    {
        $email = $request->email;
        $password = $request->password;

        // First try to find admin
        $admin = Admin::where('email', $email)->first();
        if ($admin && Hash::check($password, $admin->password)) {
            return $admin;
        }

        // Then try regular user
        $user = User::where('email', $email)->first();
        if ($user && Hash::check($password, $user->password)) {
            return $user;
        }

        // If neither found, throw validation exception
        throw ValidationException::withMessages([
            'email' => ['These credentials do not match our records.'],
        ]);
    }
}