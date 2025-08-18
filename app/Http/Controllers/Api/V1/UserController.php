<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('jabatan', 'divisi')->get();
        return UserResource::collection($users);
    }

    public function show(string $id)
    {
        $user = User::with('jabatan', 'divisi')->findOrFail($id);
        return new UserResource($user);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:admingudang,superadmin',
            'jabatan_id' => 'nullable|integer',
            'divisi_id' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Get the validated data
        $validated = $validator->validated();

        $user = User::create([
            'role' => $validated['role'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'jabatan_id' => $validated['jabatan_id'] ?? null,
            'divisi_id' => $validated['divisi_id'] ?? null,
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ], 201);
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $id,
            'password' => 'sometimes|required|string|min:8|confirmed',
            'role' => 'sometimes|required|string|in:admingudang,superadmin',
            'jabatan_id' => 'nullable|integer',
            'divisi_id' => 'nullable|integer',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name ?? $user->name,
            'email' => $request->email ?? $user->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
            'role' => $request->role ?? $user->role,
            'jabatan_id' => $request->jabatan_id ?? $user->jabatan_id,
            'divisi_id' => $request->divisi_id ?? $user->divisi_id,
        ]);
        return response()->json([
            'message' => 'User updated successfully'
        ]);
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }
}
