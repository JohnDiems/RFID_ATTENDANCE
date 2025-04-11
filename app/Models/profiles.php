<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Card;
use App\Models\Lunch;
use Carbon\Carbon;

class Profile extends Model
{
    use HasFactory;

    protected $table = 'profiles';
    public $timestamps = false;

    protected $fillable = [
        'photo',
        'StudentRFID',
        'FullName',
        'Parent',
        'EmergencyAddress',
        'EmergencyNumber',
        'Course',
        'YearLevel',
        'Gender',
        'CompleteAddress',
        'ContactNumber',
        'EmailAddress',
        'user_id'
    ];

    protected $casts = [
        'StudentRFID' => 'string',
        'ContactNumber' => 'string',
        'EmailAddress' => 'string'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'StudentRFID', 'StudentRFID');
    }

    public function card()
    {
        return $this->hasOne(Card::class);
    }

    public function latestAttendance()
    {
        return $this->hasOne(Attendance::class, 'StudentRFID', 'StudentRFID')
            ->latest('date');
    }

    public function lunches()
    {
        return $this->hasMany(Lunch::class, 'StudentRFID', 'StudentRFID');
    }

    public function todayAttendance()
    {
        return $this->attendances()
            ->whereDate('date', Carbon::today())
            ->latest()
            ->first();
    }

    public function todayLunch()
    {
        return $this->lunches()
            ->whereDate('date', Carbon::today())
            ->latest()
            ->first();
    }

    public function getFullNameAttribute($value)
    {
        return ucwords($value);
    }

    public function getParentAttribute($value)
    {
        return ucwords($value);
    }

    public function getContactNumberAttribute($value)
    {
        // Ensure contact number is properly formatted
        return preg_replace('/[^0-9+]/', '', $value);
    }

    public function getEmailAddressAttribute($value)
    {
        return strtolower($value);
    }

    public function hasCompletedAttendanceToday()
    {
        $attendance = $this->todayAttendance();
        return $attendance && $attendance->time_in && $attendance->time_out;
    }

    public function hasCompletedLunchToday()
    {
        $lunch = $this->todayLunch();
        return $lunch && $lunch->lunch_in && $lunch->lunch_out;
    }
}
