<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Lunch;
use App\Models\Attendance;
use Carbon\Carbon;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:student');
    }

    public function StudentLunchRecord(Request $request)
    {
        $user = Auth::user();
        $profile = $user->profiles()->firstOrFail();

        $query = Lunch::where('profile_id', $profile->id)->latest();

        // Apply filters
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }
        if ($request->filled('status')) {
            if ($request->status === 'completed') {
                $query->whereNotNull('time_out');
            } elseif ($request->status === 'ongoing') {
                $query->whereNull('time_out');
            }
        }

        $lunchs = $query->paginate(15);

        // Calculate statistics
        $stats = [
            'total' => $lunchs->total(),
            'completed' => $query->whereNotNull('time_out')->count(),
            'ongoing' => $query->whereNull('time_out')->count(),
            'today' => $query->whereDate('date', now())->count()
        ];

        return view('Student.StudentLunchRecord', compact('user', 'profile', 'lunchs', 'stats'));
    }

    public function StudentAttendanceRecord(Request $request)
    {
        $user = Auth::user();
        $profile = $user->profiles()->firstOrFail();

        $query = Attendance::where('profile_id', $profile->id)->latest();

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        $attendances = $query->paginate(15);

        // Calculate statistics
        $stats = [
            'total' => $attendances->total(),
            'on_time' => $query->where('status', 'present')->count(),
            'late' => $query->where('status', 'late')->count()
        ];

        return view('Student.RegularAttendance', compact('profile', 'user', 'attendances', 'stats'));
    }

    /**
     * Display user profile information
     */
    public function profile()
    {
        $user = Auth::user();
        $profile = $user->profiles()->with(['card', 'attendances' => function($query) {
            $query->latest()->take(5);
        }, 'lunches' => function($query) {
            $query->latest()->take(5);
        }])->firstOrFail();

        $stats = [
            'total_attendance' => $profile->attendances()->count(),
            'total_late' => $profile->attendances()->where('status', 'late')->count(),
            'total_lunches' => $profile->lunches()->count(),
            'this_month_attendance' => $profile->attendances()
                ->whereMonth('date', now()->month)
                ->count()
        ];

        return view('Student.profile', compact('user', 'profile', 'stats'));
    }

    /**
     * Display user activity log
     */
    public function Activity()
    {
        $user = Auth::user();
        $profile = $user->profiles()->firstOrFail();

        $activities = DB::table('activity_log')
            ->where('causer_type', 'App\Models\User')
            ->where('causer_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $deviceStats = DB::table('activity_log')
            ->where('causer_type', 'App\Models\User')
            ->where('causer_id', $user->id)
            ->select('properties->device as device', DB::raw('count(*) as count'))
            ->groupBy('properties->device')
            ->get();

        return view('Student.Activity', compact('user', 'profile', 'activities', 'deviceStats'));
    }

    // USER CHANGE PASSWORD GET
    public function StudentChange()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $profiles = $user->profiles;
            $attendances = $user->attendances;
        }
        return view('Student.StudentChange', compact('profiles','user','attendances'));
    }

    // USER CHANGE PASSWORD POST => REQUEST
     public function ChangeUser(Request $request)
    {
        // AUTH USER
        $user = Auth::user();

        // REQUEST VALIDATOR
        $request->validate([
            'current_password' => ['required', function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) {
                    $fail('The current password is incorrect.');
                }
            }],

            //NEW PASSWORD REQUIRED
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        // ENCRYPT PASSWORD HASH
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        // SUCCESS MESSAGE
        return redirect()->back()->with('success', 'Password changed successfully.');
    }

    // ADMIN LOG OUT POST REQUEST
    public function UserLogout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login');
    }


}
