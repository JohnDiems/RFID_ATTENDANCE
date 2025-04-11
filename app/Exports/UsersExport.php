<?php

namespace App\Exports;

use App\Models\User;
use App\Models\profiles;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return profiles::all();
    }

    public function headings(): array
    {
        // Define the column headings
        return [
            'id',
            'photo',
            'StudentRFID',
            'FullName',
            'Parent',
            'EmergencyAddress',
            'EmergencyNumber',
            'YearLevel',
            'Course',
            'Gender',
            'CompleteAddress',
            'ContactNumber',
            'EmailAddress',
            'Status',
            'user_id',
        ];
    }
}
