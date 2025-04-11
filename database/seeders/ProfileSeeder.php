<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profile;
use App\Models\User;
use Faker\Factory as Faker;

class ProfileSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        // Get all student users
        $students = User::where('role', 'student')->get();
        
        foreach ($students as $student) {
            $rfid = strtoupper($faker->bothify('??####??'));
            
            Profile::create([
                'user_id' => $student->id,
                'StudentRFID' => $rfid,
                'student_id' => $faker->unique()->numerify('ST######'),
                'FullName' => $student->name,
                'Gender' => $faker->randomElement(['male', 'female']),
                'birth_date' => $faker->dateTimeBetween('-25 years', '-18 years'),
                'blood_type' => $faker->randomElement(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-']),
                'YearLevel' => $faker->randomElement(['1st Year', '2nd Year', '3rd Year', '4th Year']),
                'section' => $faker->randomElement(['A', 'B', 'C']),
                'Course' => $faker->randomElement([
                    'BS Computer Science',
                    'BS Information Technology',
                    'BS Computer Engineering',
                    'BS Information Systems'
                ]),
                'Parent' => User::where('role', 'parent')
                    ->inRandomOrder()
                    ->first()
                    ->name,
                'EmergencyAddress' => $faker->address,
                'EmergencyNumber' => $faker->phoneNumber,
                'emergency_contacts' => json_encode([
                    [
                        'name' => $faker->name,
                        'relationship' => 'Parent',
                        'phone' => $faker->phoneNumber,
                        'address' => $faker->address
                    ],
                    [
                        'name' => $faker->name,
                        'relationship' => 'Guardian',
                        'phone' => $faker->phoneNumber,
                        'address' => $faker->address
                    ]
                ]),
                'CompleteAddress' => $faker->address,
                'ContactNumber' => $faker->phoneNumber,
                'EmailAddress' => $student->email,
                'medical_conditions' => json_encode([
                    'allergies' => $faker->boolean(30) ? [$faker->word, $faker->word] : [],
                    'medications' => $faker->boolean(20) ? [$faker->word] : [],
                    'conditions' => $faker->boolean(10) ? [$faker->word] : [],
                ]),
                'enrollment_status' => 'enrolled',
                'Status' => true
            ]);
        }
    }
}
