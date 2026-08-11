<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'month_year',
        'day_name',
        'title',
        'focus_target',
        'notes',
        'is_rest',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
