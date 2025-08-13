<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\User;

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
                'message' => 'Invalid credentials',
                'error' => 'invalid_credentials'
            ], 401);
        }

        // Hapus token lama
        $user->tokens()->delete();

        // Buat token baru
        $newToken = $user->createToken('api_token');
        $plainToken = $newToken->plainTextToken;

        // Simpan expires_at pakai Carbon
        $newToken->accessToken->expires_at = Carbon::now()->addMinute(config('sanctum.expiration'));
        $newToken->accessToken->save();

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $plainToken,
            'token_type' => 'Bearer',
            'expires_at' => $newToken->accessToken->expires_at
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role'=> 'string'
        ]);

        $user = User::create([
            'role' => $request->role,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
        ], 201);
    }

    public function me(Request $request)
    {
        return response()->json([
            'message' => 'User retrieved successfully',
            'user' => $request->user()
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ], 200);
    }
}
