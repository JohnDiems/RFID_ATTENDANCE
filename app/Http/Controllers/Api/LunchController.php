<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\Lunch;
use App\Models\Card;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class LunchController extends Controller
{
    public function markLunch(Request $request)
    {
        try {
            $profile = Profile::with('card')
                ->where('StudentRFID', $request->StudentRFID)
                ->where('Status', true)
                ->first();

            if (!$profile) {
                return response()->json(['error' => 'Invalid or inactive RFID card'], 400);
            }

            if (!$profile->card) {
                return response()->json(['error' => 'No lunch card assigned to this profile'], 400);
            }

            DB::beginTransaction();

            $now = Carbon::now('Asia/Manila');
            $today = Carbon::today('Asia/Manila');

            // Check if there's an existing lunch record for today
            $lunch = Lunch::where('StudentRFID', $profile->StudentRFID)
                ->whereDate('date', $today)
                ->latest()
                ->first();

            if (!$lunch) {
                // Lunch in
                $result = $this->handleLunchIn($profile, $now);
            } elseif (!$lunch->lunch_out) {
                // Lunch out
                $result = $this->handleLunchOut($profile, $now);
            } else {
                $result = response()->json(['error' => 'Lunch record already completed for today'], 400);
            }

            if ($result->getStatusCode() !== 200) {
                DB::rollBack();
                return $result;
            }

            DB::commit();
            return $result;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error marking lunch: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while marking lunch'], 500);
        }
    }

    private function handleLunchIn(Profile $profile, Carbon $now)
    {
        try {
            $card = $profile->card;
            $lunchIn = Carbon::parse($card->lunch_in, 'Asia/Manila')->setDate(
                $now->year, $now->month, $now->day
            );

            // Check if within allowed lunch time
            if (!$now->between(
                $lunchIn->copy()->subMinutes(30),
                $lunchIn->copy()->addHours(2)
            )) {
                return response()->json([
                    'error' => 'Lunch in only allowed between ' . 
                        $lunchIn->copy()->subMinutes(30)->format('g:i A') . ' and ' . 
                        $lunchIn->copy()->addHours(2)->format('g:i A')
                ], 400);
            }

            $lunch = Lunch::create([
                'profile_id' => $profile->id,
                'card_id' => $card->id,
                'StudentRFID' => $profile->StudentRFID,
                'lunch_in' => $now,
                'date' => $now->toDateString(),
                'status' => 'incomplete',
                'device_id' => request()->header('X-Device-ID', 'UNKNOWN'),
                'location' => request()->header('X-Location', 'Main Cafeteria'),
                'meta_data' => json_encode([
                    'device_type' => 'RFID Scanner',
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ])
            ]);

            return response()->json([
                'message' => 'Lunch in recorded successfully',
                'data' => [
                    'lunch' => $lunch,
                    'allowed_until' => Carbon::parse($card->lunch_out)->format('g:i A')
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error recording lunch in: ' . $e->getMessage());
            throw $e;
        }
    }

    private function handleLunchOut(Profile $profile, Carbon $now)
    {
        try {
            $lunch = Lunch::where('StudentRFID', $profile->StudentRFID)
                ->whereDate('date', Carbon::today())
                ->latest()
                ->first();

            if (!$lunch) {
                return response()->json(['error' => 'No lunch in record found for today'], 400);
            }

            if ($lunch->lunch_out) {
                return response()->json(['error' => 'Lunch out already recorded'], 400);
            }

            $card = $profile->card;
            $lunchOut = Carbon::parse($card->lunch_out, 'Asia/Manila')->setDate(
                $now->year, $now->month, $now->day
            );

            // Check if not too late
            if ($now->gt($lunchOut->copy()->addHours(1))) {
                return response()->json([
                    'error' => 'Lunch out time has passed. Maximum allowed time was ' . 
                        $lunchOut->format('g:i A')
                ], 400);
            }

            $lunch->update([
                'lunch_out' => $now,
                'status' => 'complete',
                'meta_data' => json_encode([
                    'device_type' => 'RFID Scanner',
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'lunch_out_location' => request()->header('X-Location', 'Main Cafeteria'),
                    'lunch_out_device' => request()->header('X-Device-ID', 'UNKNOWN')
                ])
            ]);

            return response()->json([
                'message' => 'Lunch out recorded successfully',
                'data' => [
                    'lunch' => $lunch,
                    'duration' => Carbon::parse($lunch->lunch_in)->diffInMinutes($lunch->lunch_out) . ' minutes'
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error recording lunch out: ' . $e->getMessage());
            throw $e;
        }
    }
}
