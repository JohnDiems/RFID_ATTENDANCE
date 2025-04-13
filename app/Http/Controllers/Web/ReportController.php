<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Lunch;
use App\Models\Profile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display the reports dashboard.
     */
    public function index()
    {
        return view('admin.reports.index');
    }

    /**
     * Display attendance reports.
     */
    public function attendance(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : Carbon::now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : Carbon::now();
        
        $attendanceData = Attendance::whereBetween('date', [$startDate, $endDate])
            ->select(
                'date',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "late" THEN 1 ELSE 0 END) as late_count'),
                DB::raw('SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present_count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        $profiles = Profile::with(['attendances' => function($query) use ($startDate, $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }])->get();
        
        return view('admin.reports.attendance', compact('attendanceData', 'profiles', 'startDate', 'endDate'));
    }

    /**
     * Display lunch reports.
     */
    public function lunch(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : Carbon::now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : Carbon::now();
        
        $lunchData = Lunch::whereBetween('date', [$startDate, $endDate])
            ->select(
                'date',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN time_out IS NOT NULL THEN 1 ELSE 0 END) as completed_count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        $profiles = Profile::with(['lunches' => function($query) use ($startDate, $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }])->get();
        
        return view('admin.reports.lunch', compact('lunchData', 'profiles', 'startDate', 'endDate'));
    }

    /**
     * Export reports to CSV/Excel.
     */
    public function export(Request $request)
    {
        $type = $request->input('type', 'attendance');
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : Carbon::now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : Carbon::now();
        $format = $request->input('format', 'csv');
        
        if ($type === 'attendance') {
            $data = Attendance::with('profile')
                ->whereBetween('date', [$startDate, $endDate])
                ->orderBy('date')
                ->get();
                
            $filename = 'attendance_report_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d');
            
            // Here you would implement the actual export logic
            // This is a placeholder for the actual implementation
            return redirect()->back()->with('info', 'Export functionality will be implemented soon.');
        } elseif ($type === 'lunch') {
            $data = Lunch::with('profile')
                ->whereBetween('date', [$startDate, $endDate])
                ->orderBy('date')
                ->get();
                
            $filename = 'lunch_report_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d');
            
            // Here you would implement the actual export logic
            // This is a placeholder for the actual implementation
            return redirect()->back()->with('info', 'Export functionality will be implemented soon.');
        }
        
        return redirect()->back()->with('error', 'Invalid report type specified.');
    }

    /**
     * Display the teacher reports dashboard.
     */
    public function teacherIndex()
    {
        return view('teacher.reports.index');
    }
}
