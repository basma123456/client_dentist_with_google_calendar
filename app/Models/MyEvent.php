<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyEvent extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'start',
        'end',
        'status',
        'is_all_day',
        'title',
        'description',
        'event_id',
    ];
}
