<?php

namespace App\Imports;

use App\Models\User;
use App\Models\profiles;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Row;

class UsersImport implements ToModel, WithValidation, OnEachRow
{
    public function model(array $row)
    {
        // Check if FullName is not empty
        if (!empty($row[2])) {
            // Create a new User
            $user = new User([
                'name' => $row[2],
                'username' => $row[1], 
                'password' => bcrypt($row[1]), 
                'role' => 'user', 
            ]);

            // Save the User
            $user->save();

            // Create a new Profile 
            $profiles = new profiles([
                'photo' => $row[0] ?? '',
                'StudentRFID' => $row[1],
                'FullName' => $row[2],
                'Parent' => $row[3] ?? '',
                'EmergencyAddress' => $row[4] ?? '',
                'EmergencyNumber' => $row[5] ?? '',
                'YearLevel' => $row[6],
                'Course' => $row[7],
                'Gender' => $row[8] ?? '',
                'CompleteAddress' => $row[9] ?? '',
                'ContactNumber' => $row[10] ?? '',
                'EmailAddress' => $row[11] ?? '',
                'Status' => 1,
                'user_id' => $user->id,
            ]);

            // Save the Profile
            $profiles->save();

            return $user;
        }

        return null; // Skip user creation for rows with empty FullName
    }


    public function onRow(Row $row)
    {
        // Additional validation
    }

    public function rules(): array
    {
        // Define validation rules if needed
        return [];
    }
}
