<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\Attendance;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        
        $attendances = Attendance::with('profile')
            ->whereDate('date', $today)
            ->latest()
            ->paginate(15);

        $stats = [
            'total' => Attendance::whereDate('date', $today)->count(),
            'present' => Attendance::whereDate('date', $today)->where('status', 'present')->count(),
            'late' => Attendance::whereDate('date', $today)->where('status', 'late')->count(),
            'expected' => Profile::where('Status', true)->count()
        ];

        $activeSchedule = Schedule::where('Status', true)
            ->where('EventTimeIn', '<=', now()->format('H:i:s'))
            ->where('EventTimeout', '>=', now()->format('H:i:s'))
            ->first();

        return view('attendance.index', compact('attendances', 'stats', 'activeSchedule'));
    }

    public function show(Request $request)
    {
        $query = Attendance::with('profile');

        // Date filter
        if ($request->filled('date')) {
            $date = Carbon::parse($request->date);
            $query->whereDate('date', $date);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by name or RFID
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('profile', function ($q) use ($search) {
                $q->where('FullName', 'like', "%{$search}%")
                    ->orWhere('StudentRFID', 'like', "%{$search}%");
            });
        }

        // Course filter
        if ($request->filled('course')) {
            $query->whereHas('profile', function ($q) use ($request) {
                $q->where('Course', $request->course);
            });
        }

        // Year Level filter
        if ($request->filled('year_level')) {
            $query->whereHas('profile', function ($q) use ($request) {
                $q->where('YearLevel', $request->year_level);
            });
        }

        $attendances = $query->latest()->paginate(15);

        // Get unique courses and year levels for filters
        $courses = Profile::select('Course')->distinct()->pluck('Course');
        $yearLevels = Profile::select('YearLevel')->distinct()->pluck('YearLevel');

        return view('attendance.show', compact('attendances', 'courses', 'yearLevels'));
    }

    public function report(Request $request)
    {
        $query = Attendance::with('profile');
        $dateRange = [];

        // Date range filter
        if ($request->filled(['start_date', 'end_date'])) {
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);
            $query->whereBetween('date', [$startDate, $endDate]);
            $dateRange = [
                'start' => $startDate,
                'end' => $endDate
            ];
        } else {
            // Default to current month
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
            $query->whereBetween('date', [$startDate, $endDate]);
            $dateRange = [
                'start' => $startDate,
                'end' => $endDate
            ];
        }

        // Course filter
        if ($request->filled('course')) {
            $query->whereHas('profile', function ($q) use ($request) {
                $q->where('Course', $request->course);
            });
        }

        // Year Level filter
        if ($request->filled('year_level')) {
            $query->whereHas('profile', function ($q) use ($request) {
                $q->where('YearLevel', $request->year_level);
            });
        }

        // Get attendance summary
        $summary = [
            'total_days' => $dateRange['start']->diffInDays($dateRange['end']) + 1,
            'total_students' => Profile::where('Status', true)->count(),
            'attendance_rate' => $this->calculateAttendanceRate($dateRange['start'], $dateRange['end']),
            'late_percentage' => $this->calculateLatePercentage($dateRange['start'], $dateRange['end'])
        ];

        // Get daily stats
        $dailyStats = [];
        $currentDate = $dateRange['start']->copy();
        while ($currentDate <= $dateRange['end']) {
            $dailyStats[] = [
                'date' => $currentDate->format('Y-m-d'),
                'total' => Attendance::whereDate('date', $currentDate)->count(),
                'present' => Attendance::whereDate('date', $currentDate)->where('status', 'present')->count(),
                'late' => Attendance::whereDate('date', $currentDate)->where('status', 'late')->count()
            ];
            $currentDate->addDay();
        }

        // Get course-wise stats
        $courseStats = DB::table('attendances')
            ->join('profiles', 'attendances.profile_id', '=', 'profiles.id')
            ->whereBetween('attendances.date', [$dateRange['start'], $dateRange['end']])
            ->select(
                'profiles.Course',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN attendances.status = "present" THEN 1 ELSE 0 END) as present'),
                DB::raw('SUM(CASE WHEN attendances.status = "late" THEN 1 ELSE 0 END) as late')
            )
            ->groupBy('profiles.Course')
            ->get();

        // Get unique courses and year levels for filters
        $courses = Profile::select('Course')->distinct()->pluck('Course');
        $yearLevels = Profile::select('YearLevel')->distinct()->pluck('YearLevel');

        return view('attendance.report', compact(
            'summary',
            'dailyStats',
            'courseStats',
            'courses',
            'yearLevels',
            'dateRange'
        ));
    }

    private function calculateAttendanceRate($startDate, $endDate)
    {
        $totalStudents = Profile::where('Status', true)->count();
        $totalDays = $startDate->diffInDays($endDate) + 1;
        $expectedAttendance = $totalStudents * $totalDays;
        
        $actualAttendance = Attendance::whereBetween('date', [$startDate, $endDate])->count();
        
        return $expectedAttendance > 0 ? ($actualAttendance / $expectedAttendance) * 100 : 0;
    }

    private function calculateLatePercentage($startDate, $endDate)
    {
        $totalAttendance = Attendance::whereBetween('date', [$startDate, $endDate])->count();
        $totalLate = Attendance::whereBetween('date', [$startDate, $endDate])
            ->where('status', 'late')
            ->count();
        
        return $totalAttendance > 0 ? ($totalLate / $totalAttendance) * 100 : 0;
    }
}
