<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Lunch;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    /**
     * Display the student attendance history.
     */
    public function attendanceHistory(Request $request)
    {
        $profile = Auth::user()->profile;
        
        if (!$profile) {
            return redirect()->route('profile.create')
                ->with('error', 'Please complete your profile first.');
        }
        
        // Date range filter
        $startDate = $request->input('start_date') 
            ? Carbon::parse($request->input('start_date')) 
            : Carbon::now()->startOfMonth();
            
        $endDate = $request->input('end_date') 
            ? Carbon::parse($request->input('end_date')) 
            : Carbon::now();
        
        // Get attendance records
        $attendances = Attendance::where('profile_id', $profile->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->with('schedule')
            ->latest('date')
            ->paginate(10);
            
        // Calculate statistics
        $stats = [
            'present' => Attendance::where('profile_id', $profile->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->where('status', 'present')
                ->count(),
                
            'late' => Attendance::where('profile_id', $profile->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->where('status', 'late')
                ->count(),
                
            'rate' => $this->calculateAttendanceRate($profile->id, $startDate, $endDate)
        ];
        
        // Prepare chart data
        $chartData = $this->prepareAttendanceChartData($profile->id, $startDate, $endDate);
        
        return view('student.attendance.history', compact('attendances', 'stats', 'chartData'));
    }
    
    /**
     * Display the student lunch history.
     */
    public function lunchHistory(Request $request)
    {
        $profile = Auth::user()->profile;
        
        if (!$profile) {
            return redirect()->route('profile.create')
                ->with('error', 'Please complete your profile first.');
        }
        
        // Date range filter
        $startDate = $request->input('start_date') 
            ? Carbon::parse($request->input('start_date')) 
            : Carbon::now()->startOfMonth();
            
        $endDate = $request->input('end_date') 
            ? Carbon::parse($request->input('end_date')) 
            : Carbon::now();
        
        // Get lunch records
        $lunches = Lunch::where('profile_id', $profile->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->latest('date')
            ->paginate(10);
            
        // Calculate statistics
        $stats = [
            'complete' => Lunch::where('profile_id', $profile->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->where('status', 'complete')
                ->count(),
                
            'incomplete' => Lunch::where('profile_id', $profile->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->where('status', 'incomplete')
                ->count()
        ];
        
        // Prepare chart data
        $chartData = $this->prepareLunchChartData($profile->id, $startDate, $endDate);
        
        return view('student.lunch.history', compact('lunches', 'stats', 'chartData'));
    }
    
    /**
     * Calculate the attendance rate for a student.
     */
    private function calculateAttendanceRate($profileId, $startDate, $endDate)
    {
        $totalDays = $startDate->diffInDays($endDate) + 1;
        $presentDays = Attendance::where('profile_id', $profileId)
            ->whereBetween('date', [$startDate, $endDate])
            ->count();
            
        return $totalDays > 0 ? ($presentDays / $totalDays) * 100 : 0;
    }
    
    /**
     * Prepare data for the attendance chart.
     */
    private function prepareAttendanceChartData($profileId, $startDate, $endDate)
    {
        $attendances = Attendance::where('profile_id', $profileId)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->get();
            
        $dates = [];
        $statuses = [];
        
        foreach ($attendances as $attendance) {
            $dates[] = Carbon::parse($attendance->date)->format('M d');
            $statuses[] = $attendance->status;
        }
        
        return [
            'dates' => $dates,
            'statuses' => $statuses
        ];
    }
    
    /**
     * Prepare data for the lunch chart.
     */
    private function prepareLunchChartData($profileId, $startDate, $endDate)
    {
        $lunches = Lunch::where('profile_id', $profileId)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->get();
            
        $dates = [];
        $durations = [];
        
        foreach ($lunches as $lunch) {
            $dates[] = Carbon::parse($lunch->date)->format('M d');
            
            if ($lunch->lunch_in && $lunch->lunch_out) {
                $duration = Carbon::parse($lunch->lunch_in)->diffInMinutes(Carbon::parse($lunch->lunch_out));
            } else {
                $duration = 0;
            }
            
            $durations[] = $duration;
        }
        
        return [
            'dates' => $dates,
            'durations' => $durations
        ];
    }
}
