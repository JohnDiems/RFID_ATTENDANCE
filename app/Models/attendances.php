<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Profile;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendances';
    public $timestamps = false;

    const STATUS_PRESENT = 'present';
    const STATUS_LATE = 'late';

    protected $fillable = [
        'photo',
        'StudentRFID',
        'FullName',
        'YearLevel',
        'Course',
        'Status',
        'time_in',
        'time_out',
        'date',
        'user_id'
    ];

    protected $casts = [
        'date' => 'date',
        'time_in' => 'datetime:g:i:s A',
        'time_out' => 'datetime:g:i:s A',
        'StudentRFID' => 'string'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function profile()
    {
        return $this->belongsTo(Profile::class, 'StudentRFID', 'StudentRFID');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('date', Carbon::today());
    }

    public function scopePresent($query)
    {
        return $query->where('Status', self::STATUS_PRESENT);
    }

    public function scopeLate($query)
    {
        return $query->where('Status', self::STATUS_LATE);
    }

    public function scopeCompleted($query)
    {
        return $query->whereNotNull('time_in')
            ->whereNotNull('time_out');
    }

    public function scopeIncomplete($query)
    {
        return $query->whereNotNull('time_in')
            ->whereNull('time_out');
    }

    public function isComplete()
    {
        return !is_null($this->time_in) && !is_null($this->time_out);
    }

    public function isLate()
    {
        return $this->Status === self::STATUS_LATE;
    }

    public function getFullNameAttribute($value)
    {
        return ucwords($value);
    }

    public function getDurationAttribute()
    {
        if (!$this->isComplete()) {
            return null;
        }

        $timeIn = Carbon::parse($this->time_in);
        $timeOut = Carbon::parse($this->time_out);
        
        return $timeOut->diff($timeIn)->format('%H:%I');
    }
}
