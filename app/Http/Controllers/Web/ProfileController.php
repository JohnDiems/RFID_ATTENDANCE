<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ProfileController extends Controller
{
    public function index()
    {
        $profiles = Profile::with(['user', 'card'])
            ->latest()
            ->paginate(15);

        $stats = [
            'total' => Profile::count(),
            'active' => Profile::where('Status', true)->count(),
            'with_cards' => Profile::has('card')->count(),
            'by_year_level' => Profile::select('YearLevel', DB::raw('count(*) as count'))
                ->groupBy('YearLevel')
                ->get()
        ];

        return view('profiles.index', compact('profiles', 'stats'));
    }

    public function create()
    {
        return view('profiles.create');
    }

    public function store(Request $request)
    {
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

        try {
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

            return redirect()->route('profiles.show', $profile)
                ->with('success', 'Profile created successfully. Default password: student123');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while creating the profile');
        }
    }

    public function show(Profile $profile)
    {
        $profile->load(['user', 'card', 'attendances', 'lunches']);

        // Get attendance statistics
        $attendanceStats = [
            'total' => $profile->attendances()->count(),
            'present' => $profile->attendances()->where('status', 'present')->count(),
            'late' => $profile->attendances()->where('status', 'late')->count(),
            'this_month' => $profile->attendances()
                ->whereMonth('date', now()->month)
                ->count()
        ];

        // Get lunch statistics
        $lunchStats = [
            'total' => $profile->lunches()->count(),
            'complete' => $profile->lunches()->where('status', 'complete')->count(),
            'incomplete' => $profile->lunches()->where('status', 'incomplete')->count(),
            'this_month' => $profile->lunches()
                ->whereMonth('date', now()->month)
                ->count()
        ];

        // Get recent activity
        $recentActivity = collect()
            ->merge($profile->attendances()->latest()->take(5)->get())
            ->merge($profile->lunches()->latest()->take(5)->get())
            ->sortByDesc('created_at')
            ->take(5);

        return view('profiles.show', compact(
            'profile',
            'attendanceStats',
            'lunchStats',
            'recentActivity'
        ));
    }

    public function edit(Profile $profile)
    {
        $profile->load(['user', 'card']);
        return view('profiles.edit', compact('profile'));
    }

    public function update(Request $request, Profile $profile)
    {
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

        try {
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

            return redirect()->route('profiles.show', $profile)
                ->with('success', 'Profile updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while updating the profile');
        }
    }

    public function updateStatus(Request $request, Profile $profile)
    {
        $request->validate([
            'status' => 'required|boolean',
            'reason' => 'required_if:status,false|string'
        ]);

        try {
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

            return redirect()->route('profiles.show', $profile)
                ->with('success', 'Profile status updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while updating the profile status');
        }
    }

    public function report(Request $request)
    {
        $query = Profile::with(['user', 'card']);

        // Status filter
        if ($request->filled('status')) {
            $query->where('Status', $request->status);
        }

        // Course filter
        if ($request->filled('course')) {
            $query->where('Course', $request->course);
        }

        // Year Level filter
        if ($request->filled('year_level')) {
            $query->where('YearLevel', $request->year_level);
        }

        // Enrollment status filter
        if ($request->filled('enrollment_status')) {
            $query->where('enrollment_status', $request->enrollment_status);
        }

        $profiles = $query->latest()->paginate(15);

        // Get statistics
        $stats = [
            'total_active' => Profile::where('Status', true)->count(),
            'total_inactive' => Profile::where('Status', false)->count(),
            'with_cards' => Profile::has('card')->count(),
            'without_cards' => Profile::doesntHave('card')->count()
        ];

        // Get course distribution
        $courseStats = Profile::select('Course', DB::raw('count(*) as count'))
            ->groupBy('Course')
            ->get();

        // Get year level distribution
        $yearLevelStats = Profile::select('YearLevel', DB::raw('count(*) as count'))
            ->groupBy('YearLevel')
            ->get();

        // Get enrollment status distribution
        $enrollmentStats = Profile::select('enrollment_status', DB::raw('count(*) as count'))
            ->groupBy('enrollment_status')
            ->get();

        return view('profiles.report', compact(
            'profiles',
            'stats',
            'courseStats',
            'yearLevelStats',
            'enrollmentStats'
        ));
    }

    public function import()
    {
        return view('profiles.import');
    }

    public function processImport(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt'
        ]);

        try {
            $file = $request->file('file');
            $path = $file->getRealPath();
            $records = array_map('str_getcsv', file($path));

            // Remove header row
            $header = array_shift($records);

            DB::beginTransaction();

            $imported = 0;
            $errors = [];

            foreach ($records as $index => $record) {
                try {
                    $data = array_combine($header, $record);

                    // Create user
                    $user = User::create([
                        'name' => $data['FullName'],
                        'email' => $data['EmailAddress'],
                        'username' => Str::slug($data['student_id']),
                        'password' => Hash::make('student123'),
                        'role' => 'student',
                        'status' => 'active',
                        'timezone' => 'Asia/Manila'
                    ]);

                    // Create profile
                    Profile::create(array_merge(
                        $data,
                        [
                            'user_id' => $user->id,
                            'enrollment_status' => 'enrolled',
                            'Status' => true
                        ]
                    ));

                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
                }
            }

            DB::commit();

            return redirect()->route('profiles.index')
                ->with('success', "Imported {$imported} profiles successfully" . 
                    (count($errors) > 0 ? ". Errors: " . implode(", ", $errors) : ""));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while importing profiles');
        }
    }
}
