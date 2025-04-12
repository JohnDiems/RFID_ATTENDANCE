<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\Profile;
use Carbon\Carbon;
use Faker\Factory as Faker;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        // Get all profiles
        $profiles = Profile::all();
        
        // Generate attendance records for the last 7 days
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            
            foreach ($profiles as $profile) {
                // 90% chance of attendance
                if ($faker->boolean(90)) {
                    $baseTimeIn = Carbon::create($date->year, $date->month, $date->day, 7, 0, 0);
                    $baseTimeOut = Carbon::create($date->year, $date->month, $date->day, 16, 0, 0);
                    
                    // Random variation in time
                    $timeIn = $baseTimeIn->addMinutes($faker->numberBetween(-30, 30));
                    $timeOut = $baseTimeOut->addMinutes($faker->numberBetween(-30, 30));
                    
                    // Determine if late
                    $isLate = $timeIn->format('H:i:s') > '07:30:00';
                    
                    Attendance::create([
                        'profile_id' => $profile->id,
                        'date' => $date,
                        'time_in' => $timeIn,
                        'time_out' => $timeOut,
                        'status' => $isLate ? 'late' : 'present'
                    ]);
                }
            }
        }
    }
}
