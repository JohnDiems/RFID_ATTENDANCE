<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'System Administrator',
            'username' => 'admin',
            'email' => 'admin@rfidsystem.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'status' => 'active',
            'timezone' => 'Asia/Manila',
            'email_verified_at' => now(),
        ]);

        // Create staff user
        User::create([
            'name' => 'Staff Member',
            'username' => 'staff',
            'email' => 'staff@rfidsystem.com',
            'password' => Hash::make('staff123'),
            'role' => 'staff',
            'status' => 'active',
            'timezone' => 'Asia/Manila',
            'email_verified_at' => now(),
        ]);

        // Create 50 student users
        for ($i = 1; $i <= 50; $i++) {
            User::create([
                'name' => "Student $i",
                'username' => "student$i",
                'email' => "student$i@rfidsystem.com",
                'password' => Hash::make('student123'),
                'role' => 'student',
                'status' => 'active',
                'timezone' => 'Asia/Manila',
                'email_verified_at' => now(),
            ]);
        }

        // Create 20 parent users
        for ($i = 1; $i <= 20; $i++) {
            User::create([
                'name' => "Parent $i",
                'username' => "parent$i",
                'email' => "parent$i@rfidsystem.com",
                'password' => Hash::make('parent123'),
                'role' => 'parent',
                'status' => 'active',
                'timezone' => 'Asia/Manila',
                'email_verified_at' => now(),
            ]);
        }
    }
}
