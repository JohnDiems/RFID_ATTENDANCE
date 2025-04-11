<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Profile;
use App\Models\Card;

class Lunch extends Model
{
    use HasFactory;

    protected $table = 'lunches';
    public $timestamps = false;

    protected $fillable = [
        'photo',
        'StudentRFID',
        'FullName',
        'YearLevel',
        'Course',
        'lunch_in',
        'lunch_out',
        'date',
        'card_id'
    ];

    protected $casts = [
        'date' => 'date',
        'lunch_in' => 'datetime:g:i:s A',
        'lunch_out' => 'datetime:g:i:s A'
    ];

    public function profile()
    {
        return $this->belongsTo(Profile::class, 'StudentRFID', 'StudentRFID');
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
        return !is_null($this->lunch_in) && !is_null($this->lunch_out);
    }
}
