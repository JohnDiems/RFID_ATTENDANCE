<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\Attendance;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    private const TIMEZONE = 'Asia/Manila';
    private const DEFAULT_TIME_IN = '07:30:00';
    private const DEFAULT_TIME_OUT = '16:30:00';
    private const ALLOWED_EARLY_MINUTES = 60; // 1 hour early allowed
    private const ALLOWED_LATE_MINUTES = 120; // 2 hours late allowed

    public function markAttendance(Request $request)
    {
        $request->validate([
            'StudentRFID' => 'required|string|min:3|max:50'
        ]);

        try {
            return DB::transaction(function () use ($request) {
                $profile = Profile::where('StudentRFID', $request->StudentRFID)
                    ->where('Status', true)
                    ->with(['card' => function ($query) {
                        $query->where('status', 'active');
                    }])
                    ->first();

                if (!$profile) {
                    return response()->json(['error' => 'Invalid or inactive RFID card'], 400);
                }

                if (!$profile->card) {
                    return response()->json(['error' => 'No active card found for this student'], 400);
                }

                $now = Carbon::now(self::TIMEZONE);
                $today = Carbon::today(self::TIMEZONE);

                // Check if there's an existing attendance record for today
                $attendance = Attendance::where('StudentRFID', $profile->StudentRFID)
                    ->whereDate('date', $today)
                    ->latest()
                    ->first();

                if (!$attendance) {
                    // Time in
                    $result = $this->handleTimeIn($profile, $now);
                } elseif (!$attendance->time_out) {
                    // Time out
                    $result = $this->handleTimeOut($attendance, $now);
                } else {
                    $result = response()->json([
                        'error' => 'Attendance already completed for today',
                        'attendance' => $attendance
                    ], 400);
                }

                return $result;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error marking attendance: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while marking attendance'], 500);
        }
    }

    private function handleTimeIn(Profile $profile, Carbon $now)
    {
        // Get active schedule or use default
        $schedule = Schedule::where('Status', true)
            ->where('EventTimeIn', '<=', $now->format('H:i:s'))
            ->where('EventTimeout', '>=', $now->format('H:i:s'))
            ->first();

        $eventTimeIn = $schedule 
            ? Carbon::parse($schedule->EventTimeIn, self::TIMEZONE)
            : Carbon::parse(self::DEFAULT_TIME_IN, self::TIMEZONE);

        // Check if within allowed time window
        $allowedStart = $eventTimeIn->copy()->subMinutes(self::ALLOWED_EARLY_MINUTES);
        $allowedEnd = $eventTimeIn->copy()->addMinutes(self::ALLOWED_LATE_MINUTES);

        if (!$now->between($allowedStart, $allowedEnd)) {
            return response()->json([
                'error' => 'Time in only allowed between ' . 
                    $allowedStart->format('g:i A') . ' and ' . 
                    $allowedEnd->format('g:i A')
            ], 400);
        }

        $isLate = $now->gt($eventTimeIn);

        $attendance = Attendance::create([
            'profile_id' => $profile->id,
            'StudentRFID' => $profile->StudentRFID,
            'time_in' => $now,
            'date' => $now->toDateString(),
                'status' => $isLate ? 'late' : 'present',
                'device_id' => request()->header('X-Device-ID', 'UNKNOWN'),
                'location' => request()->header('X-Location', 'Main Gate'),
                'meta_data' => json_encode([
                    'device_type' => 'RFID Scanner',
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ]),
                'schedule_data' => $schedule ? json_encode([
                    'schedule_id' => $schedule->id,
                    'event_time_in' => $schedule->EventTimeIn,
                    'event_time_out' => $schedule->EventTimeout
                ]) : null
            ]);

            return response()->json([
                'message' => 'Time in recorded successfully',
                'data' => [
                    'attendance' => $attendance,
                    'status' => $isLate ? 'late' : 'on time'
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error recording time in: ' . $e->getMessage());
            throw $e;
        }
    }

    private function handleTimeOut(Attendance $attendance, Carbon $now)
    {
        // Get active schedule or use default
        $schedule = Schedule::where('Status', true)
            ->where('EventTimeIn', '<=', $now->format('H:i:s'))
            ->where('EventTimeout', '>=', $now->format('H:i:s'))
            ->first();

        $eventTimeOut = $schedule 
            ? Carbon::parse($schedule->EventTimeout, self::TIMEZONE)
            : Carbon::parse(self::DEFAULT_TIME_OUT, self::TIMEZONE);

        // Check minimum duration (at least 2 hours after time in)
        $minTimeOut = $attendance->time_in->copy()->addHours(2);
        if ($now->lt($minTimeOut)) {
            return response()->json([
                'error' => 'Too early to time out. Minimum duration is 2 hours. ' . 
                    'Earliest time out allowed is ' . $minTimeOut->format('g:i A')
            ], 400);
        }

        // Check if before allowed time out
        if ($now->lt($eventTimeOut)) {
            return response()->json([
                'error' => 'Cannot time out before scheduled time: ' . $eventTimeOut->format('g:i A')
            ], 400);
        }

        // Update attendance record
        $attendance->update([
            'time_out' => $now,
            'meta_data' => json_encode(array_merge(
                json_decode($attendance->meta_data, true) ?? [],
                [
                    'device_type' => 'RFID Scanner',
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'time_out_location' => request()->header('X-Location', 'Main Gate'),
                    'time_out_device' => request()->header('X-Device-ID', 'UNKNOWN'),
                    'duration_hours' => $now->diffInHours($attendance->time_in),
                    'duration_minutes' => $now->diffInMinutes($attendance->time_in) % 60
                ]
            ))
        ]);

        return response()->json([
            'message' => 'Time out recorded successfully',
            'data' => [
                'attendance_id' => $attendance->id,
                'student_name' => $attendance->profile->FullName,
                'time_in' => $attendance->time_in->format('g:i A'),
                'time_out' => $now->format('g:i A'),
                'duration' => sprintf(
                    '%d hours %d minutes',
                    $now->diffInHours($attendance->time_in),
                    $now->diffInMinutes($attendance->time_in) % 60
                )
            ]
        ]);
    }

    private function getActiveSchedule()
    {
        $now = Carbon::now(self::TIMEZONE);
        return Schedule::where('Status', true)
            ->where('EventTimeIn', '<=', $now->format('H:i:s'))
            ->where('EventTimeout', '>=', $now->format('H:i:s'))
            ->first();
    }
}
