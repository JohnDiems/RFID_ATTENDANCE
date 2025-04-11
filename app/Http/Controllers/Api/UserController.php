<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'role' => 'required|in:admin,staff,student,parent',
                'status' => 'required|in:active,inactive',
                'timezone' => 'required|string'
            ]);

            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'status' => $request->status,
                'timezone' => $request->timezone,
                'email_verified_at' => now(),
                'preferences' => json_encode([
                    'notifications' => [
                        'email' => true,
                        'browser' => true
                    ],
                    'theme' => 'light'
                ])
            ]);

            DB::commit();

            return response()->json([
                'message' => 'User created successfully',
                'data' => $user
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating user: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while creating the user'], 500);
        }
    }

    public function update(Request $request, User $user)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users,username,' . $user->id,
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'role' => 'required|in:admin,staff,student,parent',
                'status' => 'required|in:active,inactive',
                'timezone' => 'required|string',
                'password' => 'nullable|string|min:8'
            ]);

            DB::beginTransaction();

            $updateData = [
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'role' => $request->role,
                'status' => $request->status,
                'timezone' => $request->timezone
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);

            DB::commit();

            return response()->json([
                'message' => 'User updated successfully',
                'data' => $user
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating user: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while updating the user'], 500);
        }
    }

    public function updatePassword(Request $request, User $user)
    {
        try {
            $request->validate([
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:8|different:current_password',
                'confirm_password' => 'required|string|same:new_password'
            ]);

            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json(['error' => 'Current password is incorrect'], 400);
            }

            DB::beginTransaction();

            $user->update([
                'password' => Hash::make($request->new_password)
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Password updated successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating password: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while updating the password'], 500);
        }
    }

    public function destroy(User $user)
    {
        try {
            if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
                return response()->json(['error' => 'Cannot delete the last admin user'], 400);
            }

            DB::beginTransaction();

            // Soft delete associated profile if exists
            if ($user->profile) {
                $user->profile->update([
                    'Status' => false,
                    'meta_data' => json_encode(array_merge(
                        json_decode($user->profile->meta_data, true) ?? [],
                        [
                            'deleted_by' => auth()->user()->name,
                            'deleted_at' => now()->toDateTimeString(),
                            'reason' => 'User account deleted'
                        ]
                    ))
                ]);
                $user->profile->delete();
            }

            $user->delete();

            DB::commit();

            return response()->json([
                'message' => 'User deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting user: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while deleting the user'], 500);
        }
    }
}
