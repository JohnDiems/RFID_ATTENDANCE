<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lunch;
use App\Models\Profile;
use App\Models\Card;
use Carbon\Carbon;
use Faker\Factory as Faker;

class LunchSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        // Get all profiles with their cards
        $profiles = Profile::with('card')->get();
        
        // Generate lunch records for the last 7 days
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            
            foreach ($profiles as $profile) {
                // 85% chance of lunch attendance
                if ($faker->boolean(85) && $profile->card) {
                    $baseLunchIn = Carbon::parse($profile->card->lunch_in)->setDate(
                        $date->year, $date->month, $date->day
                    );
                    $baseLunchOut = Carbon::parse($profile->card->lunch_out)->setDate(
                        $date->year, $date->month, $date->day
                    );
                    
                    // Random variation in time
                    $lunchIn = $baseLunchIn->addMinutes($faker->numberBetween(-15, 15));
                    $lunchOut = $baseLunchOut->addMinutes($faker->numberBetween(-15, 15));
                    
                    Lunch::create([
                        'profile_id' => $profile->id,
                        'card_id' => $profile->card->id,
                        'StudentRFID' => $profile->StudentRFID,
                        'time_in' => $lunchIn,
                        'time_out' => $lunchOut,
                        'date' => $date,
                        'status' => 'complete',
                        'device_id' => 'CAFE-' . $faker->randomElement(['01', '02']),
                        'location' => $faker->randomElement(['Main Cafeteria', 'Side Cafeteria']),
                        'meta_data' => json_encode([
                            'device_type' => 'RFID Scanner',
                            'temperature' => $faker->randomFloat(1, 36.1, 37.2)
                        ]),
                        'menu_data' => json_encode([
                            'meal_type' => $faker->randomElement(['Regular', 'Vegetarian', 'Halal']),
                            'items' => [
                                'main' => $faker->randomElement(['Rice', 'Noodles']),
                                'viand' => $faker->randomElement(['Chicken', 'Fish', 'Pork', 'Vegetables']),
                                'drink' => $faker->randomElement(['Water', 'Juice', 'Soda'])
                            ],
                            'cost' => $faker->randomFloat(2, 50, 150)
                        ])
                    ]);
                }
            }
        }
    }
}
