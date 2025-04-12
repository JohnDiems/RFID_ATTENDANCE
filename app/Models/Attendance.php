<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'profile_id',
        'date',
        'time_in',
        'time_out',
        'status'
    ];

    protected $casts = [
        'date' => 'date',
        'time_in' => 'datetime',
        'time_out' => 'datetime'
    ];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}
