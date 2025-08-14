<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $credentials['email'])->first();
        
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Delete existing tokens
        $user->tokens()->delete();
        
        // Create new token (simpler version without expiration parameter for debugging)
        $token = $user->createToken('api_token');

        return response()->json([
            'message' => 'Login successful',
            'user' => $user->makeHidden(['created_at', 'updated_at']),
            'token' => $token->plainTextToken,
            'token_type' => 'Bearer',
        ]);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:admingudang,superadmin', // Added your default roles
            'jabatan_id' => 'nullable|integer',
            'divisi_id' => 'nullable|integer',
        ]);

        $user = User::create([
            'role' => $validated['role'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'jabatan_id' => $validated['jabatan_id'] ?? null,
            'divisi_id' => $validated['divisi_id'] ?? null,
        ]);

        // Create token
        $token = $user->createToken('api_token');

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user->makeHidden(['created_at', 'updated_at']),
            'token' => $token->plainTextToken,
            'token_type' => 'Bearer',
        ], 201);
    }

    public function me(Request $request)
    {
        // Load relationships only if they exist
        $user = $request->user();
        
        // Try to load relationships, but handle if they don't exist
        try {
            $user->load(['jabatan', 'divisi']);
        } catch (\Exception $e) {
            // If relationships don't exist, just continue without them
        }
        
        return response()->json([
            'message' => 'User retrieved successfully',
            'user' => $user->makeHidden(['created_at', 'updated_at'])
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ], 200);
    }

    public function logoutAll(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out from all devices successfully'
        ], 200);
    }

    public function refreshToken(Request $request)
    {
        $user = $request->user();
        
        // Delete current token
        $request->user()->currentAccessToken()->delete();
        
        // Create new token
        $token = $user->createToken('api_token');

        return response()->json([
            'message' => 'Token refreshed successfully',
            'token' => $token->plainTextToken,
            'token_type' => 'Bearer',
        ]);
    }
}