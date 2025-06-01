<?php
// app/Http/Controllers/API/AuthController.php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PersonalAccessToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:seller,buyer',
            'device_name' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Create token manually for MongoDB compatibility
        $deviceName = $request->device_name ?? $request->ip();
        $plainTextToken = Str::random(40);
        
        $accessToken = PersonalAccessToken::create([
            'name' => $deviceName,
            'token' => hash('sha256', $plainTextToken),
            'abilities' => ['*'],
            'tokenable_type' => get_class($user),
            'tokenable_id' => $user->_id,
        ]);

        // Format token as Sanctum expects: {id}|{plaintext}
        $token = $accessToken->_id . '|' . $plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
            'message' => 'Registration successful'
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'nullable|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Create token manually for MongoDB compatibility
        $deviceName = $request->device_name ?? $request->ip();
        $plainTextToken = Str::random(40);
        
        $accessToken = PersonalAccessToken::create([
            'name' => $deviceName,
            'token' => hash('sha256', $plainTextToken),
            'abilities' => ['*'],
            'tokenable_type' => get_class($user),
            'tokenable_id' => $user->_id,
        ]);

        // Format token as Sanctum expects: {id}|{plaintext}
        $token = $accessToken->_id . '|' . $plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        // Find and delete the current token
        $tokenId = $request->bearerToken();
        
        if ($tokenId && strpos($tokenId, '|') !== false) {
            [$id, $token] = explode('|', $tokenId, 2);
            
            $accessToken = PersonalAccessToken::find($id);
            if ($accessToken && hash_equals($accessToken->token, hash('sha256', $token))) {
                $accessToken->delete();
            }
        }

        return response()->json(['message' => 'Logged out successfully']);
    }
}