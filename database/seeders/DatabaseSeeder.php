<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // First create all users
        $this->call(UserSeeder::class);

        // // Then create profiles for students
        $this->call(ProfileSeeder::class);

        // // Create cards for profiles
        // $this->call(CardSeeder::class);

        // // Create schedules
        // $this->call(ScheduleSeeder::class);

        // // Generate attendance records
        // $this->call(AttendanceSeeder::class);

        // // Generate lunch records
        // $this->call(LunchSeeder::class);
    }
}
