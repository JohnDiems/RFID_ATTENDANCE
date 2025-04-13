<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'schedule';
    protected $fillable = [
        'EventName',
        'EventTimeIn',
        'EventTimeout',
        'EventDate',
        'Status'
    ];

}