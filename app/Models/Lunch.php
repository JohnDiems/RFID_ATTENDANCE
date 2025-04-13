<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\Profile;
use App\Models\Card;

class Lunch extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'lunches';

    protected $fillable = [
        'profile_id',
        'card_id',
        'StudentRFID',
        'date',
        'time_in',
        'time_out',
        'status',
        'device_id',
        'location',
        'meta_data',
        'menu_data'
    ];

    protected $casts = [
        'date' => 'date',
        'time_in' => 'datetime',
        'time_out' => 'datetime',
        'meta_data' => 'array',
        'menu_data' => 'array'
    ];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    public function card()
    {
        return $this->belongsTo(Card::class);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('date', now());
    }

    public function isComplete()
    {
        return !is_null($this->time_in) && !is_null($this->time_out);
    }
}
