<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if the environment is either 'local' or 'development'
        if (app()->environment('local', 'development')) {
            $name = 'Assumption Administrator';
            $username = 'admin';
            $password = 'admin';

            DB::table('users')->insert([
                'name' => $name,
                'username' => $username,
                'password' => Hash::make($password),
                'role' => 'admin',
            ]);

            $this->command->info('Admin account created successfully!');
        } else {
            $this->command->info('Admin account creation skipped in non-local/development environment.');
        }
    }
}
