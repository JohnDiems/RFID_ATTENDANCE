<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Profile;
use App\Models\Announcement;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AttendancesController extends Controller
{
    private const DEFAULT_TIME_IN = '07:30';
    private const DEFAULT_TIME_OUT = '16:30';

    /**
     * Get the scheduled time for a specific profile
     */
    protected function getScheduledTime(Profile $profile, string $type = 'in'): string
    {
        $today = Carbon::today();
        $dayOfWeek = strtolower($today->format('l'));

        $schedule = Schedule::where('profile_id', $profile->id)
            ->where('status', true)
            ->where(function ($query) use ($dayOfWeek) {
                $query->where($dayOfWeek, true)
                    ->orWhere('is_default', true);
            })
            ->first();

        return $type === 'in' ? 
            ($schedule ? $schedule->time_in : self::DEFAULT_TIME_IN) : 
            ($schedule ? $schedule->time_out : self::DEFAULT_TIME_OUT);
    }
    
    /**
     * Display the landing page with today's attendance records
     */
    public function home()
    {
        try {
            $attendances = Attendance::with(['profile:id,full_name,year,course,photo'])
                ->whereDate('date', Carbon::today())
                ->latest()
                ->get();

            $announcement = Announcement::latest()->first();
            
            $stats = [
                'total' => $attendances->count(),
                'present' => $attendances->where('status', 'present')->count(),
                'late' => $attendances->where('status', 'late')->count(),
                'time' => Carbon::now('Asia/Manila')->format('g:i A')
            ];

            return view('LandingPage.home', compact('attendances', 'announcement', 'stats'));
        } catch (\Exception $e) {
            Log::error('Error loading landing page: ' . $e->getMessage());
            return view('LandingPage.home', [
                'error' => 'Unable to load attendance data. Please refresh the page.',
                'attendances' => collect(),
                'stats' => [],
                'announcement' => null
            ]);
        }
    }

    public function markAttendance(Request $request)
    {
        $request->validate([
            'StudentRFID' => 'required|string|min:8|max:11'
        ]);

        try {
            Log::info('Processing attendance request', [
                'rfid' => $request->input('StudentRFID'),
                'time' => Carbon::now('Asia/Manila')->toDateTimeString()
            ]);

            return DB::transaction(function () use ($request) {
                $studentRFID = trim($request->input('StudentRFID'));
                
                $profile = Profile::where('StudentRFID', $studentRFID)->first();
                
                if (!$profile) {
                    Log::info('RFID not found', ['rfid' => $studentRFID]);
                    return response()->json([
                        'status' => 'error',
                        'message' => 'RFID card not found. Please try again.'
                    ], 404);
                }

                Log::info('Profile found', [
                    'profile_id' => $profile->id,
                    'name' => $profile->FullName
                ]);

                $now = Carbon::now('Asia/Manila');
                $today = $now->format('Y-m-d');
                
                // Check for existing attendance
                $existingAttendance = Attendance::where('profile_id', $profile->id)
                    ->whereDate('date', $today)
                    ->first();

                if ($existingAttendance) {
                    if ($existingAttendance->time_out === null) {
                        // Time out
                        $existingAttendance->time_out = $now;
                        $existingAttendance->save();
                        
                        Log::info('Time out recorded', [
                            'profile_id' => $profile->id,
                            'attendance_id' => $existingAttendance->id
                        ]);

                        return response()->json([
                            'status' => 'success',
                            'message' => 'Time out recorded successfully',
                            'data' => [
                                'profile' => $profile,
                                'attendance' => $existingAttendance
                            ]
                        ]);
                    }
                    
                    Log::info('Attendance already completed', [
                        'profile_id' => $profile->id,
                        'attendance_id' => $existingAttendance->id
                    ]);

                    return response()->json([
                        'status' => 'error',
                        'message' => 'Attendance already completed for today'
                    ]);
                }

                // New time in
                $scheduledTimeIn = $this->getScheduledTime($profile, 'in');
                $scheduledTime = Carbon::parse($today . ' ' . $scheduledTimeIn);
                
                $attendance = new Attendance([
                    'profile_id' => $profile->id,
                    'date' => $today,
                    'time_in' => $now,
                    'status' => $now->gt($scheduledTime) ? 'late' : 'present'
                ]);
                
                $attendance->save();

                Log::info('Time in recorded', [
                    'profile_id' => $profile->id,
                    'attendance_id' => $attendance->id,
                    'status' => $attendance->status
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Time in recorded successfully',
                    'data' => [
                        'profile' => $profile,
                        'attendance' => $attendance
                    ]
                ]);
            });
        } catch (\Exception $e) {
            Log::error('Attendance marking error: ' . $e->getMessage(), [
                'rfid' => $request->input('StudentRFID'),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to record attendance'
            ], 500);
        }
    }
}
