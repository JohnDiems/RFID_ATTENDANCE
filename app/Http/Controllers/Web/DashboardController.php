<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\Attendance;
use App\Models\Lunch;
use App\Models\Card;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        // Basic statistics for cards
        $profilesCount = Profile::count();
        $todayAttendanceCount = Attendance::whereDate('date', $today)->count();
        $todayLateCount = Attendance::whereDate('date', $today)->where('status', 'late')->count();
        $activeScheduleCount = DB::table('schedules')->where('Status', true)->count();

        // Detailed statistics
        $stats = [
            'total_students' => $profilesCount,
            'active_students' => Profile::where('Status', true)->count(),
            'inactive_students' => Profile::where('Status', false)->count(),
            'today_attendance' => $todayAttendanceCount,
            'today_late' => $todayLateCount,
            'today_on_time' => $todayAttendanceCount - $todayLateCount,
            'today_lunch' => Lunch::whereDate('date', $today)->count(),
            'monthly_attendance' => Attendance::where('date', '>=', $thisMonth)->count(),
            'monthly_late' => Attendance::where('date', '>=', $thisMonth)->where('status', 'late')->count(),
            'active_cards' => Card::where('status', 'active')->count(),
            'inactive_cards' => Card::where('status', '!=', 'active')->count(),
            'total_schedules' => DB::table('schedules')->count(),
            'active_schedules' => $activeScheduleCount
        ];

        // Get attendance trend for the last 7 days
        $attendanceTrend = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dayStats = Attendance::whereDate('date', $date)
                ->selectRaw('COUNT(*) as total, SUM(CASE WHEN status = "late" THEN 1 ELSE 0 END) as late')
                ->first();

            $attendanceTrend->push([
                'date' => $date->format('M d'),
                'total' => $dayStats->total ?? 0,
                'late' => $dayStats->late ?? 0,
                'on_time' => ($dayStats->total ?? 0) - ($dayStats->late ?? 0)
            ]);
        }

        // Get lunch trend for the last 7 days
        $lunchTrend = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $lunchStats = Lunch::whereDate('date', $date)
                ->selectRaw('COUNT(*) as total, COUNT(CASE WHEN time_out IS NOT NULL THEN 1 END) as completed')
                ->first();

            $lunchTrend->push([
                'date' => $date->format('M d'),
                'total' => $lunchStats->total ?? 0,
                'completed' => $lunchStats->completed ?? 0,
                'ongoing' => ($lunchStats->total ?? 0) - ($lunchStats->completed ?? 0)
            ]);
        }

        // Get recent activity
        $recentActivity = collect();

        // Recent attendances
        $recentAttendances = Attendance::with('profile')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($attendance) {
                return [
                    'type' => 'attendance',
                    'profile' => $attendance->profile,
                    'action' => $attendance->time_out ? 'Time Out' : 'Time In',
                    'time' => $attendance->time_out ?? $attendance->time_in,
                    'status' => $attendance->status
                ];
            });

        // Recent lunches
        $recentLunches = Lunch::with('profile')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($lunch) {
                return [
                    'type' => 'lunch',
                    'profile' => $lunch->profile,
                    'action' => $lunch->time_out ? 'Time Out' : 'Time In',
                    'time' => $lunch->time_out ?? $lunch->time_in,
                    'status' => $lunch->status
                ];
            });

        // Merge and sort activities
        $recentActivity = $recentAttendances->concat($recentLunches)
            ->sortByDesc('time')
            ->take(5)
            ->values();

        // Get students by year level
        $studentsByYear = Profile::select('YearLevel', DB::raw('count(*) as total'))
            ->groupBy('YearLevel')
            ->get();

        // Get students by course
        $studentsByCourse = Profile::select('Course', DB::raw('count(*) as total'))
            ->groupBy('Course')
            ->get();

        return view('dashboard.index', compact(
            'stats',
            'attendanceTrend',
            'lunchTrend',
            'recentActivity',
            'studentsByYear',
            'studentsByCourse'
        ));
    }

    public function adminDashboard()
    {
        return $this->index();
    }

    public function teacherDashboard()
    {
        return $this->index();
    }

    public function studentDashboard()
    {
        $profile = auth()->user()->profile;
        
        if (!$profile) {
            return redirect()->route('profile.create')
                ->with('error', 'Please complete your profile first.');
        }

        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        // Get student's attendance records
        $attendanceStats = [
            'today' => Attendance::where('profile_id', $profile->id)
                ->whereDate('date', $today)
                ->first(),
            'monthly_attendance' => Attendance::where('profile_id', $profile->id)
                ->where('date', '>=', $thisMonth)
                ->count(),
            'monthly_late' => Attendance::where('profile_id', $profile->id)
                ->where('date', '>=', $thisMonth)
                ->where('status', 'late')
                ->count()
        ];

        // Get student's lunch records
        $lunchStats = [
            'today' => Lunch::where('profile_id', $profile->id)
                ->whereDate('date', $today)
                ->first(),
            'monthly_lunches' => Lunch::where('profile_id', $profile->id)
                ->where('date', '>=', $thisMonth)
                ->count()
        ];

        // Get attendance trend for the last 7 days
        $attendanceTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $record = Attendance::where('profile_id', $profile->id)
                ->whereDate('date', $date)
                ->first();
            
            $attendanceTrend[] = [
                'date' => $date->format('Y-m-d'),
                'status' => $record ? $record->status : 'absent',
                'time_in' => $record ? $record->time_in : null,
                'time_out' => $record ? $record->time_out : null
            ];
        }

        // Get lunch trend for the last 7 days
        $lunchTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $record = Lunch::where('profile_id', $profile->id)
                ->whereDate('date', $date)
                ->first();
            
            $lunchTrend[] = [
                'date' => $date->format('Y-m-d'),
                'status' => $record ? $record->status : 'none',
                'time_in' => $record ? $record->time_in : null,
                'time_out' => $record ? $record->time_out : null
            ];
        }

        return view('dashboard.student', compact(
            'profile',
            'attendanceStats',
            'lunchStats',
            'attendanceTrend',
            'lunchTrend'
        ));
    }
}
