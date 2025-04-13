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
use Illuminate\Support\Facades\Storage;

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
        // Debug: Log all form data
        \Log::info('Profile creation attempt with data:', $request->all());
        
        try {
            $validated = $request->validate([
                'StudentRFID' => 'required|unique:profiles,StudentRFID',
                'student_id' => 'required|unique:profiles,student_id',
                'FullName' => 'required|string|max:255',
                'Gender' => 'required|in:Male,Female',
                'birth_date' => 'required|date',
                'blood_type' => 'required|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
                'YearLevel' => 'required|string',
                'Course' => 'required|string',
                'Section' => 'required|string',
                'Parent' => 'required|string',
                'EmergencyAddress' => 'required|string',
                'EmergencyNumber' => 'required|string',
                'CompleteAddress' => 'required|string',
                'ContactNumber' => 'required|string',
                'EmailAddress' => 'required|email|unique:users,email',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            
            // Debug: Log validation success
            \Log::info('Profile validation passed');

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
            
            // Debug: Log user creation
            \Log::info('User created with ID: ' . $user->id);

            // Prepare profile data
            $profileData = array_merge(
                $request->except('photo'),
                [
                    'user_id' => $user->id,
                    'enrollment_status' => 'enrolled',
                    'Status' => true,
                    'section' => $request->Section, // Map Section to section
                    'emergency_contacts' => json_encode([
                        'name' => $request->Parent,
                        'number' => $request->EmergencyNumber,
                        'address' => $request->EmergencyAddress
                    ]),
                    'medical_conditions' => json_encode([
                        'allergies' => [],
                        'medications' => [],
                        'conditions' => []
                    ])
                ]
            );
            
            // Debug: Log profile data
            \Log::info('Profile data prepared', $profileData);

            // Handle photo upload
            if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
                // Store new photo with a unique name
                $fileName = time() . '_' . $request->file('photo')->getClientOriginalName();
                $photoPath = $request->file('photo')->storeAs('profile-photos', $fileName, 'public');
                $profileData['photo'] = $photoPath;
                
                // Debug: Log photo upload
                \Log::info('Photo uploaded: ' . $photoPath);
            }
            
            // Create profile
            $profile = Profile::create($profileData);
            
            // Debug: Log profile creation
            \Log::info('Profile created with ID: ' . $profile->id);

            DB::commit();
            
            // Debug: Log transaction committed
            \Log::info('Database transaction committed successfully');

            return redirect()->route('admin.profiles')
                ->with('success', 'Profile created successfully. Default password: student123');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Debug: Log validation errors
            \Log::error('Validation error during profile creation:', $e->errors());
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            // Debug: Log any other errors
            \Log::error('Error during profile creation: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return back()->with('error', 'An error occurred while creating the profile: ' . $e->getMessage())->withInput();
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

    /**
     * Edit the authenticated user's own profile.
     */
    public function editOwn()
    {
        $user = auth()->user();
        $profile = $user->profile;
        
        if (!$profile) {
            return redirect()->route('profile.create')
                ->with('error', 'Please complete your profile first.');
        }
        
        return view('profiles.edit-own', compact('profile'));
    }

    public function update(Request $request, Profile $profile)
    {
        $request->validate([
            'FullName' => 'required|string|max:255',
            'Gender' => 'required|in:male,female',
            'birth_date' => 'required|date',
            'blood_type' => 'required|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'ContactNumber' => 'required|string',
            'CompleteAddress' => 'required|string',
            'Parent' => 'required|string',
            'EmergencyNumber' => 'required|string',
            'EmergencyAddress' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'StudentRFID' => 'nullable|string',
            'YearLevel' => 'nullable|string',
            'Course' => 'nullable|string',
        ]);

        // Start with a clean array of data to update
        $data = [
            'FullName' => $request->FullName,
            'Gender' => $request->Gender,
            'birth_date' => $request->birth_date,
            'blood_type' => $request->blood_type,
            'ContactNumber' => $request->ContactNumber,
            'CompleteAddress' => $request->CompleteAddress,
            'Parent' => $request->Parent,
            'EmergencyNumber' => $request->EmergencyNumber,
            'EmergencyAddress' => $request->EmergencyAddress,
            'StudentRFID' => $request->StudentRFID,
            'YearLevel' => $request->YearLevel,
            'Course' => $request->Course,
        ];
        
        // Handle photo upload
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            // Delete old photo if exists and it's not the default
            if ($profile->photo && $profile->photo !== 'default.jpg' && Storage::disk('public')->exists($profile->photo)) {
                Storage::disk('public')->delete($profile->photo);
            }
            
            // Store new photo with a unique name
            $fileName = time() . '_' . $request->file('photo')->getClientOriginalName();
            $photoPath = $request->file('photo')->storeAs('profile-photos', $fileName, 'public');
            $data['photo'] = $photoPath;
        }

        $profile->update($data);

        if ($profile->user) {
            $profile->user->update([
                'name' => $request->FullName
            ]);
        }

        return redirect()->route('admin.profiles.show', $profile)
            ->with('success', 'Profile updated successfully');
    }

    public function updateStatus(Request $request, Profile $profile)
    {
        $request->validate([
            'status' => 'required|boolean'
        ]);

        $profile->update([
            'Status' => $request->status
        ]);

        return redirect()->back()
            ->with('success', 'Profile status updated successfully.');
    }

    /**
     * Update the authenticated user's own profile.
     */
    public function updateOwn(Request $request)
    {
        $user = auth()->user();
        $profile = $user->profile;
        
        if (!$profile) {
            return redirect()->route('profile.create')
                ->with('error', 'Please complete your profile first.');
        }
        
        $request->validate([
            'FullName' => 'required|string|max:255',
            'Gender' => 'required|in:male,female',
            'birth_date' => 'required|date',
            'blood_type' => 'required|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'ContactNumber' => 'required|string',
            'CompleteAddress' => 'required|string',
            'Parent' => 'required|string',
            'EmergencyNumber' => 'required|string',
            'EmergencyAddress' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // Start with a clean array of data to update
        $data = [
            'FullName' => $request->FullName,
            'Gender' => $request->Gender,
            'birth_date' => $request->birth_date, // Store directly without parsing
            'blood_type' => $request->blood_type,
            'ContactNumber' => $request->ContactNumber,
            'CompleteAddress' => $request->CompleteAddress,
            'Parent' => $request->Parent,
            'EmergencyNumber' => $request->EmergencyNumber,
            'EmergencyAddress' => $request->EmergencyAddress,
        ];
        
        // Handle photo upload
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            // Delete old photo if exists and it's not the default
            if ($profile->photo && $profile->photo !== 'default.jpg' && Storage::disk('public')->exists($profile->photo)) {
                Storage::disk('public')->delete($profile->photo);
            }
            
            // Store new photo with a unique name
            $fileName = time() . '_' . $request->file('photo')->getClientOriginalName();
            $photoPath = $request->file('photo')->storeAs('profile-photos', $fileName, 'public');
            $data['photo'] = $photoPath;
        }
        
        // Update the profile with the prepared data
        $updated = $profile->update($data);
        
        // Update the user's name to match the profile
        $user->update([
            'name' => $request->FullName
        ]);
        
        if ($updated) {
            return redirect()->route('profile.edit')
                ->with('success', 'Your profile has been updated successfully! All your information is now up to date.');
        } else {
            return redirect()->route('profile.edit')
                ->with('error', 'There was a problem updating your profile. Please try again.');
        }
    }

    /**
     * Display the user settings page.
     */
    public function settings()
    {
        $user = auth()->user();
        return view('profiles.settings', compact('user'));
    }

    /**
     * Update the user settings.
     */
    public function updateSettings(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
            'username' => 'required|string|unique:users,username,' . $user->id,
            'current_password' => 'required_with:password|password',
            'password' => 'nullable|string|min:8|confirmed',
            'timezone' => 'required|string',
        ]);
        
        $userData = [
            'email' => $request->email,
            'username' => $request->username,
            'timezone' => $request->timezone,
        ];
        
        // Update password if provided
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }
        
        $user->update($userData);
        
        return redirect()->route('profile.settings')
            ->with('success', 'Your settings have been updated successfully.');
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
