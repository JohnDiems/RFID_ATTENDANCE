<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Card;
use App\Models\Profile;
use Carbon\Carbon;
use Faker\Factory as Faker;

class CardSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        // Get all profiles
        $profiles = Profile::all();
        
        foreach ($profiles as $profile) {
            $lunchStart = Carbon::createFromTime(11, 30);
            $lunchEnd = Carbon::createFromTime(13, 0);
            
            Card::create([
                'profile_id' => $profile->id,
                'card_number' => strtoupper($faker->bothify('CARD####??')),
                'status' => 'active',
                'lunch_in' => $lunchStart->format('H:i:s'),
                'lunch_out' => $lunchEnd->format('H:i:s'),
                'issued_at' => now(),
                'expires_at' => now()->addYear(),
                'access_permissions' => json_encode([
                    'can_access_library' => true,
                    'can_access_lab' => true,
                    'can_access_gym' => true
                ]),
                'meta_data' => json_encode([
                    'issuer' => 'System',
                    'batch' => '2025-A',
                    'notes' => 'Regular student card'
                ])
            ]);
        }
    }
}
