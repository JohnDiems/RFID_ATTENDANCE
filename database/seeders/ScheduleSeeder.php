<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default schedules
        DB::table('schedules')->insert([
            [
                'name' => 'Regular Schedule',
                'start_time' => '08:00:00',
                'end_time' => '17:00:00',
                'lunch_start' => '12:00:00',
                'lunch_end' => '13:00:00',
                'Status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Morning Shift',
                'start_time' => '07:00:00',
                'end_time' => '12:00:00',
                'lunch_start' => null,
                'lunch_end' => null,
                'Status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Afternoon Shift',
                'start_time' => '13:00:00',
                'end_time' => '18:00:00',
                'lunch_start' => null,
                'lunch_end' => null,
                'Status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
