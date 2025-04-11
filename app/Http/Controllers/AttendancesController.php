<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Profile;
use App\Models\Announcement;
use App\Models\Lunch;
use App\Models\Card;
use App\Models\Schedule;
use Carbon\Carbon;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AttendancesController extends Controller
{
    private const LUNCH_COOLDOWN = 5; // seconds
    private const DEFAULT_TIME_IN = '07:30';
    private const DEFAULT_TIME_OUT = '16:30';

    /**
     * Get the scheduled time for a specific profile
     *
     * @param Profile $profile
     * @param string $type 'in' or 'out'
     * @return string Time in HH:mm format
     */
    protected function getScheduledTime(Profile $profile, string $type = 'in'): string
    {
        $today = Carbon::today();
        $dayOfWeek = strtolower($today->format('l'));

        $schedule = Schedule::where('profile_id', $profile->id)
            ->where('Status', true)
            ->where(function ($query) use ($dayOfWeek) {
                $query->where($dayOfWeek, true)
                    ->orWhere('is_default', true);
            })
            ->first();

        if (!$schedule) {
            return $type === 'in' ? self::DEFAULT_TIME_IN : self::DEFAULT_TIME_OUT;
        }

        return $type === 'in' ? $schedule->time_in : $schedule->time_out;
    }
    
    /**
     * Display the landing page with today's attendance records
     */
    public function home()
    {
        try {
            // Get today's attendance records
            $attendances = Attendance::with(['profile' => function($query) {
                $query->select('id', 'FullName', 'YearLevel', 'Course', 'photo');
            }])
            ->whereDate('date', Carbon::today())
            ->latest()
            ->get();

            // Get latest announcement
            $announcement = Announcement::latest()->first();

            // Get statistics
            $stats = [
                'total' => $attendances->count(),
                'present' => $attendances->where('Status', 'present')->count(),
                'late' => $attendances->where('Status', 'late')->count(),
                'time' => Carbon::now('Asia/Manila')->format('g:i A')
            ];

            return view('LandingPage.home', compact('attendances', 'announcement', 'stats'));
        } catch (\Exception $e) {
            Log::error('Error loading landing page', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Still try to render the view even if data loading fails
            return view('LandingPage.home')
                ->with('error', 'Unable to load attendance data. Please try refreshing the page.')
                ->with('attendances', collect())
                ->with('stats', [])
                ->with('announcement', null);
        }
    }

    public function markAttendance(Request $request)
    {
        $request->validate([
            'StudentRFID' => 'required|string|min:3|max:50'
        ]);

        try {
            return DB::transaction(function () use ($request) {
                $studentRFID = trim($request->input('StudentRFID'));
                $profile = Profile::where('StudentRFID', $studentRFID)->first();

                if (!$profile) {
                    return response()->json(['error' => 'Invalid Student RFID Card'], 400);
                }

                $now = Carbon::now('Asia/Manila');
                
                // Check for lunch attendance
                if ($card = $this->getLunchCard($profile)) {
                    if ($this->isLunchTime($now, $card->lunch_in, $card->lunch_out)) {
                        return $this->handleLunchAttendance($studentRFID, $now, $profile, $card);
                    }
                }

                // Get scheduled time for today
                $scheduledTimeIn = Carbon::parse($this->getScheduledTime($profile, 'in'), 'Asia/Manila');
                $scheduledTimeOut = Carbon::parse($this->getScheduledTime($profile, 'out'), 'Asia/Manila');

                // Check if within schedule window (30 minutes before scheduled time to 2 hours after)
                $windowStart = $scheduledTimeIn->copy()->subMinutes(30);
                $windowEnd = $scheduledTimeOut->copy()->addHours(2);

                if (!$now->between($windowStart, $windowEnd)) {
                    return response()->json([
                        'error' => 'Attendance can only be marked between ' . 
                                  $windowStart->format('g:i A') . ' and ' . 
                                  $windowEnd->format('g:i A')
                    ], 400);
                }

                // Determine if late
                $status = $now->gt($scheduledTimeIn->addMinutes(15)) ? 'late' : 'present';

                // Regular attendance check
                $attendance = Attendance::where('StudentRFID', $studentRFID)
                    ->whereDate('date', Carbon::today())
                    ->latest()
                    ->first();

                $timeIn = Carbon::parse(self::DEFAULT_TIME_IN, 'Asia/Manila');
                $timeOut = Carbon::parse(self::DEFAULT_TIME_OUT, 'Asia/Manila');

                // Handle time out if already timed in
                if ($attendance && !$attendance->time_out) {
                    if ($now->lt($timeOut)) {
                        return response()->json(['error' => 'Cannot time out before ' . $timeOut->format('g:i A')], 400);
                    }
                    return $this->handleTimeOut($profile, $now);
                }

                // Prevent multiple attendance records
                if ($attendance && $attendance->time_out) {
                    return response()->json(['error' => 'Attendance already completed for today'], 400);
                }

                // Handle new time in
                $status = $now->gt($timeIn) ? 'late' : 'present';
                return $this->handleTimeIn($studentRFID, $now, $profile, $status);
            });
        } catch (\Exception $e) {
            Log::error('Error in attendance marking: ' . $e->getMessage());
            return response()->json(['error' => 'Error processing attendance'], 500);
        }
    }

    // New method to check if the current time is within lunch hours
    /**
     * Check if the current time is within lunch hours and if lunch can be marked
     *
     * @param Carbon $now Current time
     * @param string $lunchIn Lunch start time
     * @param string $lunchOut Lunch end time
     * @return bool
     */
    private function isLunchTime($now, $lunchIn, $lunchOut)
    {
        try {
            $lunchInTime = Carbon::parse($lunchIn, 'Asia/Manila');
            $lunchOutTime = Carbon::parse($lunchOut, 'Asia/Manila');

            // Add buffer time (15 minutes before and after scheduled lunch)
            $lunchWindowStart = $lunchInTime->copy()->subMinutes(15);
            $lunchWindowEnd = $lunchOutTime->copy()->addMinutes(15);

            return $now->between($lunchWindowStart, $lunchWindowEnd);
        } catch (\Exception $e) {
            Log::error('Error checking lunch time', [
                'error' => $e->getMessage(),
                'lunch_in' => $lunchIn,
                'lunch_out' => $lunchOut
            ]);
            return false;
        }
    }

    private function getLunchCard(Profile $profile)
    {
        return Card::where('profile_id', $profile->id)->first();
    }

    private function getActiveSchedule()
    {
        return Schedule::whereDate('EventDate', Carbon::today())->first();
    }

    private function isWithinSchedule($now, $eventTimeIn, $eventTimeOut)
    {
        return $now->between($eventTimeIn, $eventTimeOut);
    }

    private function handleLunchIn($studentRFID, $now, Profile $profile, Card $card)
    {
        try {
            $lunch = Lunch::create([
                'StudentRFID' => $studentRFID,
                'photo' => $profile->photo,
                'FullName' => $profile->FullName,
                'YearLevel' => $profile->YearLevel,
                'Course' => $profile->Course,
                'lunch_in' => $now->format('g:i:s A'),
                'date' => Carbon::today(),
                'card_id' => $card->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Lunch In recorded successfully',
                'data' => [
                    'photo' => $profile->photo,
                    'FullName' => $profile->FullName,
                    'YearLevel' => $profile->YearLevel,
                    'Course' => $profile->Course,
                    'date' => Carbon::today()->format('Y-m-d'),
                    'lunch_in' => $lunch->lunch_in,
                    'lunch_out' => null
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error recording lunch in: ' . $e->getMessage());
            throw $e;
        }
    }

    private function handleLunchAttendance($studentRFID, $now, Profile $profile, Card $card)
    {
        try {
            $existingLunch = Lunch::where('StudentRFID', $studentRFID)
                ->whereDate('date', Carbon::today())
                ->first();

            if (!$existingLunch) {
                return $this->handleLunchIn($studentRFID, $now, $profile, $card);
            }

            // Check if already completed lunch
            if ($existingLunch->lunch_out) {
                return $this->handleTimeOut($profile, $now);
            }

            // Check cooldown period
            $lastTapInTime = Carbon::parse($existingLunch->lunch_in, 'Asia/Manila');
            $timeElapsed = $now->diffInSeconds($lastTapInTime);

            if ($timeElapsed < self::LUNCH_COOLDOWN) {
                return response()->json([
                    'error' => 'Please wait ' . self::LUNCH_COOLDOWN . ' seconds before recording lunch out'
                ], 400);
            }

            // Record lunch out
            $existingLunch->lunch_out = $now->format('g:i:s A');
            $existingLunch->save();

            return response()->json([
                'success' => true,
                'message' => 'Lunch Out recorded successfully',
                'data' => [
                    'photo' => $profile->photo,
                    'FullName' => $profile->FullName,
                    'YearLevel' => $profile->YearLevel,
                    'Course' => $profile->Course,
                    'date' => Carbon::today()->format('Y-m-d'),
                    'lunch_in' => $existingLunch->lunch_in,
                    'lunch_out' => $existingLunch->lunch_out
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error handling lunch attendance: ' . $e->getMessage());
            throw $e;
        }
    }

    private function handleTimeIn($studentRFID, $now, Profile $profile, $status)
    {
        try {
            $attendance = Attendance::create([
                'StudentRFID' => $studentRFID,
                'photo' => $profile->photo,
                'FullName' => $profile->FullName,
                'YearLevel' => $profile->YearLevel,
                'Course' => $profile->Course,
                'time_in' => $now->format('g:i:s A'),
                'Status' => $status,
                'date' => Carbon::today(),
                'user_id' => $profile->user_id
            ]);

            // Get latest announcement
            $announcement = Announcement::latest()->first();
            $announcementContent = $announcement ? $announcement->Content : 'No announcements';

            // Send SMS notification
            try {
                $this->sendSMSNotification(
                    $profile->ContactNumber,
                    "Dear {$profile->Parent}, {$profile->FullName} has timed in at {$attendance->time_in}.",
                    $announcementContent
                );
            } catch (\Exception $e) {
                Log::warning('SMS notification failed: ' . $e->getMessage());
                // Continue execution even if SMS fails
            }

            return response()->json([
                'success' => true,
                'message' => 'Time in recorded successfully',
                'data' => [
                    'photo' => $profile->photo,
                    'FullName' => $profile->FullName,
                    'YearLevel' => $profile->YearLevel,
                    'Course' => $profile->Course,
                    'date' => Carbon::today()->format('Y-m-d'),
                    'time_in' => $attendance->time_in,
                    'time_out' => null,
                    'Status' => $status
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error recording time in: ' . $e->getMessage());
            throw $e;
        }
    }

    private function handleTimeOut(Profile $profile, $now)
    {
        try {
            $attendance = Attendance::where('StudentRFID', $profile->StudentRFID)
                ->whereDate('date', Carbon::today())
                ->latest()
                ->first();

            if (!$attendance) {
                return response()->json(['error' => 'No time in record found for today'], 400);
            }

            if ($attendance->time_out) {
                return response()->json(['error' => 'Time out already recorded'], 400);
            }

            // Check schedule
            if ($schedule = $this->getActiveSchedule()) {
                $eventTimeOut = Carbon::parse($schedule->EventTimeout, 'Asia/Manila');
                if ($now->lt($eventTimeOut)) {
                    return response()->json([
                        'error' => 'Cannot time out before scheduled time: ' . $eventTimeOut->format('g:i A')
                    ], 400);
                }
            } else {
                // Regular time out check (4:30 PM)
                $defaultTimeOut = Carbon::today('Asia/Manila')->setTimeFromTimeString(self::DEFAULT_TIME_OUT);
                if ($now->lt($defaultTimeOut)) {
                    return response()->json([
                        'error' => 'Cannot time out before ' . $defaultTimeOut->format('g:i A')
                    ], 400);
                }
            }

            // Record time out
            $attendance->time_out = $now->format('g:i:s A');
            $attendance->save();

            // Get latest announcement
            $announcement = Announcement::latest()->first();
            $announcementContent = $announcement ? $announcement->Content : 'No announcements';

            // Send SMS notification
            try {
                $this->sendSMSNotification(
                    $profile->ContactNumber,
                    "Dear {$profile->Parent}, {$profile->FullName} has timed out at {$attendance->time_out}.",
                    $announcementContent
                );
            } catch (\Exception $e) {
                Log::warning('SMS notification failed: ' . $e->getMessage());
                // Continue execution even if SMS fails
            }

            return response()->json([
                'success' => true,
                'message' => 'Time out recorded successfully',
                'data' => [
                    'photo' => $profile->photo,
                    'FullName' => $profile->FullName,
                    'YearLevel' => $profile->YearLevel,
                    'Course' => $profile->Course,
                    'date' => Carbon::today()->format('Y-m-d'),
                    'time_in' => $attendance->time_in,
                    'time_out' => $attendance->time_out,
                    'Status' => $attendance->Status
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error recording time out: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Check if the attendance record is valid and regular
     *
     * @param Attendance|null $attendance
     * @return bool
     */
    private function isRegularAttendance($attendance)
    {
        if (!$attendance) {
            return false;
        }

        // Must have time_in but not time_out
        if (!$attendance->time_in || $attendance->time_out) {
            return false;
        }

        // Must be from today
        if (!Carbon::parse($attendance->date)->isToday()) {
            return false;
        }

        return true;
    }

    private function sendSMSNotification($to, $mainMessage, $announcementContent)
    {
        try {
            $twilioSid = config('services.twilio.sid');
            $twilioToken = config('services.twilio.token');
            $twilioPhoneNumber = config('services.twilio.phone_number');

            if (!$twilioSid || !$twilioToken || !$twilioPhoneNumber) {
                Log::warning('SMS notification skipped: Missing Twilio credentials');
                return;
            }

            $client = new Client($twilioSid, $twilioToken);
            $fullMessage = trim($mainMessage) . "\n\nAnnouncement:\n" . trim($announcementContent);

            $twilioMessage = $client->messages->create(
                $to,
                [
                    'from' => $twilioPhoneNumber,
                    'body' => $fullMessage
                ]
            );

            Log::info("SMS sent successfully to {$to}. SID: {$twilioMessage->sid}");
        } catch (\Exception $e) {
            Log::error('SMS notification failed: ' . $e->getMessage());
            throw $e; // Re-throw to be caught by caller
        }
    }



    private function sendSMSNotification($to, $mainMessage, $announcementContent)
    {
        try {
            $twilioSid = config('services.twilio.sid');
            $twilioToken = config('services.twilio.token');
            $twilioPhoneNumber = config('services.twilio.phone_number');

            if (!$twilioSid || !$twilioToken || !$twilioPhoneNumber) {
                Log::warning('SMS notification skipped: Missing Twilio credentials');
                return;
            }

            $client = new Client($twilioSid, $twilioToken);
            $fullMessage = trim($mainMessage) . "\n\nAnnouncement:\n" . trim($announcementContent);

            $twilioMessage = $client->messages->create(
                $to,
                [
                    'from' => $twilioPhoneNumber,
                    'body' => $fullMessage
                ]
            );

            Log::info("SMS sent successfully to {$to}. SID: {$twilioMessage->sid}");
        } catch (\Exception $e) {
            Log::error('SMS notification failed: ' . $e->getMessage());
            throw $e; // Re-throw to be caught by caller
        }
    }
}
