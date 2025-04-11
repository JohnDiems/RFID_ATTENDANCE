<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\Lunch;
use App\Models\Card;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LunchController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        
        $lunches = Lunch::with(['profile', 'card'])
            ->whereDate('date', $today)
            ->latest()
            ->paginate(15);

        $stats = [
            'total' => Lunch::whereDate('date', $today)->count(),
            'complete' => Lunch::whereDate('date', $today)->where('status', 'complete')->count(),
            'incomplete' => Lunch::whereDate('date', $today)->where('status', 'incomplete')->count(),
            'expected' => Card::where('status', 'active')->count()
        ];

        return view('lunch.index', compact('lunches', 'stats'));
    }

    public function show(Request $request)
    {
        $query = Lunch::with(['profile', 'card']);

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

        $lunches = $query->latest()->paginate(15);

        // Get unique courses and year levels for filters
        $courses = Profile::select('Course')->distinct()->pluck('Course');
        $yearLevels = Profile::select('YearLevel')->distinct()->pluck('YearLevel');

        return view('lunch.show', compact('lunches', 'courses', 'yearLevels'));
    }

    public function report(Request $request)
    {
        $query = Lunch::with(['profile', 'card']);
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

        // Get lunch summary
        $summary = [
            'total_days' => $dateRange['start']->diffInDays($dateRange['end']) + 1,
            'total_students' => Card::where('status', 'active')->count(),
            'lunch_rate' => $this->calculateLunchRate($dateRange['start'], $dateRange['end']),
            'completion_rate' => $this->calculateCompletionRate($dateRange['start'], $dateRange['end'])
        ];

        // Get daily stats
        $dailyStats = [];
        $currentDate = $dateRange['start']->copy();
        while ($currentDate <= $dateRange['end']) {
            $dailyStats[] = [
                'date' => $currentDate->format('Y-m-d'),
                'total' => Lunch::whereDate('date', $currentDate)->count(),
                'complete' => Lunch::whereDate('date', $currentDate)->where('status', 'complete')->count(),
                'incomplete' => Lunch::whereDate('date', $currentDate)->where('status', 'incomplete')->count()
            ];
            $currentDate->addDay();
        }

        // Get course-wise stats
        $courseStats = DB::table('lunches')
            ->join('profiles', 'lunches.profile_id', '=', 'profiles.id')
            ->whereBetween('lunches.date', [$dateRange['start'], $dateRange['end']])
            ->select(
                'profiles.Course',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN lunches.status = "complete" THEN 1 ELSE 0 END) as complete'),
                DB::raw('SUM(CASE WHEN lunches.status = "incomplete" THEN 1 ELSE 0 END) as incomplete')
            )
            ->groupBy('profiles.Course')
            ->get();

        // Get time distribution
        $timeStats = DB::table('lunches')
            ->whereBetween('date', [$dateRange['start'], $dateRange['end']])
            ->select(
                DB::raw('HOUR(lunch_in) as hour'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        // Get unique courses and year levels for filters
        $courses = Profile::select('Course')->distinct()->pluck('Course');
        $yearLevels = Profile::select('YearLevel')->distinct()->pluck('YearLevel');

        return view('lunch.report', compact(
            'summary',
            'dailyStats',
            'courseStats',
            'timeStats',
            'courses',
            'yearLevels',
            'dateRange'
        ));
    }

    private function calculateLunchRate($startDate, $endDate)
    {
        $totalStudents = Card::where('status', 'active')->count();
        $totalDays = $startDate->diffInDays($endDate) + 1;
        $expectedLunches = $totalStudents * $totalDays;
        
        $actualLunches = Lunch::whereBetween('date', [$startDate, $endDate])->count();
        
        return $expectedLunches > 0 ? ($actualLunches / $expectedLunches) * 100 : 0;
    }

    private function calculateCompletionRate($startDate, $endDate)
    {
        $totalLunches = Lunch::whereBetween('date', [$startDate, $endDate])->count();
        $completeLunches = Lunch::whereBetween('date', [$startDate, $endDate])
            ->where('status', 'complete')
            ->count();
        
        return $totalLunches > 0 ? ($completeLunches / $totalLunches) * 100 : 0;
    }
}
