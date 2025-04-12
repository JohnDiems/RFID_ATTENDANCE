<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'StudentRFID',
        'student_id',
        'FullName',
        'Gender',
        'birth_date',
        'blood_type',
        'YearLevel',
        'section',
        'Course',
        'Parent',
        'EmergencyAddress',
        'EmergencyNumber',
        'emergency_contacts',
        'CompleteAddress',
        'ContactNumber',
        'EmailAddress',
        'medical_conditions',
        'enrollment_status',
        'Status',
        'photo'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'emergency_contacts' => 'array',
        'medical_conditions' => 'array',
        'Status' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function card()
    {
        return $this->hasOne(Card::class);
    }
}
