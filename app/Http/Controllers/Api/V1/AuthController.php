<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Http\Resources\Api\V1\UserResource;
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
            'role' => 'required|string|in:admingudang,superadmin',
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

        $token = $user->createToken('api_token');

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user->makeHidden(['created_at', 'updated_at']),
            'token' => $token->plainTextToken,
            'token_type' => 'Bearer',
        ], 201);
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
        
        $request->user()->currentAccessToken()->delete();
        
        $token = $user->createToken('api_token');
        
        return response()->json([
            'message' => 'Token refreshed successfully',
            'token' => $token->plainTextToken,
            'token_type' => 'Bearer',
        ]);
    }
    public function me(Request $request)
    {
        try {
            $currentToken = $request->user()->currentAccessToken();
            
            if (!$currentToken) {
                return response()->json([
                    'message' => 'Token not found'
                ], 401);
            }
    
            if ($currentToken->expires_at && Carbon::now()->isAfter($currentToken->expires_at)) {
                // Delete the expired token
                $currentToken->delete();
                
                return response()->json([
                    'message' => 'Token expired',
                    'error' => 'TOKEN_EXPIRED'
                   ], 401);
            }
    
            $user = $request->user();
            $user->load(['jabatan', 'divisi']);
            return new UserResource($user);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Token expired or invalid',
                'error' => 'TOKEN_EXPIRED'
            ], 401);
        }
    }
    public function update(Request $request) {
        try {
            $user = $request->user();
            $validated = $request->validate([
                'name' => 'sometimes|string',
                'email' => 'sometimes|string|email',
                'password' => 'sometimes|string|confirmed'
            ]);

            $updateData = [];
            if (isset($validated['name'])) {
                $updateData['name'] = $validated['name'];
            }
            if (isset($validated['email'])) {
                $updateData['email'] = $validated['email'];
            }
            if (isset($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $user->update($updateData);

            return response()->json([
                'message' => 'Berhasil update'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'gagal update',
                'error' => $e->getMessage()
            ], 422);
        }
    }
}