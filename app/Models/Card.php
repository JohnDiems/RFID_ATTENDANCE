<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Profile;
use App\Models\Lunch;

class Card extends Model
{
    use HasFactory;

    protected $table = 'cards';
    public $timestamps = false;

    protected $fillable = [
        'profile_id',
        'lunch_in',
        'lunch_out'
    ];

    protected $casts = [
        'lunch_in' => 'datetime:H:i',
        'lunch_out' => 'datetime:H:i'
    ];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    public function lunches()
    {
        return $this->hasMany(Lunch::class);
    }

    public function getActiveTimeRange()
    {
        return [
            'start' => $this->lunch_in,
            'end' => $this->lunch_out
        ];
    }
}
