<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'StudentRFID' => 'required|unique:profiles,StudentRFID',
                'student_id' => 'required|unique:profiles,student_id',
                'FullName' => 'required|string|max:255',
                'Gender' => 'required|in:male,female',
                'birth_date' => 'required|date',
                'blood_type' => 'required|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
                'YearLevel' => 'required|string',
                'Course' => 'required|string',
                'section' => 'required|string',
                'Parent' => 'required|string',
                'EmergencyAddress' => 'required|string',
                'EmergencyNumber' => 'required|string',
                'emergency_contacts' => 'required|array',
                'CompleteAddress' => 'required|string',
                'ContactNumber' => 'required|string',
                'EmailAddress' => 'required|email|unique:users,email'
            ]);

            DB::beginTransaction();

            // Create user account
            $user = User::create([
                'name' => $request->FullName,
                'email' => $request->EmailAddress,
                'username' => Str::slug($request->student_id),
                'password' => Hash::make('student123'), // Default password
                'role' => 'student',
                'status' => 'active',
                'timezone' => 'Asia/Manila'
            ]);

            // Create profile
            $profile = Profile::create(array_merge(
                $request->all(),
                [
                    'user_id' => $user->id,
                    'enrollment_status' => 'enrolled',
                    'Status' => true,
                    'medical_conditions' => json_encode([
                        'allergies' => [],
                        'medications' => [],
                        'conditions' => []
                    ])
                ]
            ));

            DB::commit();

            return response()->json([
                'message' => 'Profile created successfully',
                'data' => [
                    'profile' => $profile,
                    'user' => $user
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating profile: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while creating the profile'], 500);
        }
    }

    public function update(Request $request, Profile $profile)
    {
        try {
            $request->validate([
                'StudentRFID' => 'required|unique:profiles,StudentRFID,' . $profile->id,
                'student_id' => 'required|unique:profiles,student_id,' . $profile->id,
                'FullName' => 'required|string|max:255',
                'Gender' => 'required|in:male,female',
                'birth_date' => 'required|date',
                'blood_type' => 'required|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
                'YearLevel' => 'required|string',
                'Course' => 'required|string',
                'section' => 'required|string',
                'Parent' => 'required|string',
                'EmergencyAddress' => 'required|string',
                'EmergencyNumber' => 'required|string',
                'emergency_contacts' => 'required|array',
                'CompleteAddress' => 'required|string',
                'ContactNumber' => 'required|string',
                'enrollment_status' => 'required|in:enrolled,graduated,withdrawn,suspended'
            ]);

            DB::beginTransaction();

            // Update profile
            $profile->update($request->all());

            // Update associated user
            if ($profile->user) {
                $profile->user->update([
                    'name' => $request->FullName,
                    'status' => $request->enrollment_status === 'enrolled' ? 'active' : 'inactive'
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Profile updated successfully',
                'data' => [
                    'profile' => $profile->fresh(['user']),
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating profile: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while updating the profile'], 500);
        }
    }

    public function updateStatus(Request $request, Profile $profile)
    {
        try {
            $request->validate([
                'status' => 'required|boolean',
                'reason' => 'required_if:status,false|string'
            ]);

            DB::beginTransaction();

            $profile->update([
                'Status' => $request->status,
                'meta_data' => json_encode(array_merge(
                    json_decode($profile->meta_data, true) ?? [],
                    [
                        'status_updated_by' => auth()->user()->name,
                        'status_updated_at' => now()->toDateTimeString(),
                        'status_reason' => $request->reason ?? null
                    ]
                ))
            ]);

            // Update user status if profile is deactivated
            if (!$request->status && $profile->user) {
                $profile->user->update(['status' => 'inactive']);
            }

            DB::commit();

            return response()->json([
                'message' => 'Profile status updated successfully',
                'data' => $profile->fresh(['user'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating profile status: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while updating the profile status'], 500);
        }
    }
}
